<?php
include('db.php');
session_start();

if(isset($_GET['old_id']) && isset($_SESSION['temp_booking'])) {
    $old_id = mysqli_real_escape_string($conn, $_GET['old_id']);
    $data = $_SESSION['temp_booking'];
    $dest_name = $_SESSION['temp_dest'];
    $u_name = $_SESSION['username'];
    
    // 1. Purani booking delete karein
    mysqli_query($conn, "DELETE FROM bookings WHERE booking_id='$old_id'");

    // 2. Nayi booking ka data set karein
    $u_pkg = $data['p_package'];
    $u_date = $data['p_date'];
    $u_adults = (int)$data['p_adults'];
    $u_minors = (int)$data['p_minors']; 
    $u_phone = $data['p_phone'];
    $booking_id = "TW-" . rand(100000, 999999);

    // 3. Price fetch karein destination table se asli rate nikalne ke liye
    $res = mysqli_query($conn, "SELECT * FROM destinations WHERE name='$dest_name'");
    $p = mysqli_fetch_assoc($res);
    
    // Yahan hum wahi logic use kar rahe hain jo details.php mein tha
    if($u_pkg == 'Economy') {
        $pkg_price = $p['price_economy'];
    } elseif($u_pkg == 'Premium') {
        $pkg_price = $p['price_premium'];
    } else {
        $pkg_price = $p['price_luxury'];
    }

    // Total fare calculate karna (Adults + Minors) * Price
    $total_fare = $pkg_price * ($u_adults + $u_minors);

    // 4. Nayi booking insert karein sahi price ke sath
    $query = "INSERT INTO bookings (booking_id, user_name, dest_name, travel_date, persons, minors, package_type, total_price, phone) 
              VALUES ('$booking_id', '$u_name', '$dest_name', '$u_date', '$u_adults', '$u_minors', '$u_pkg', '$total_fare', '$u_phone')";

    if(mysqli_query($conn, $query)) {
        header("Location: my_bookings.php?msg=Rebooked Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit();
}
?>