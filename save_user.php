<?php
include('db.php');
session_start();

if(isset($_POST['book_btn'])) {
    // 1. Session se logged-in user ka naam lena
    $user = $_SESSION['username'];

    // 2. Form se baki details lena
    $destination = mysqli_real_escape_string($conn, $_POST['d_name']);
    $persons = (int)$_POST['u_persons'];
    $pkg = mysqli_real_escape_string($conn, $_POST['u_package']);
    $t_date = $_POST['u_date'];
    
    // 3. Price Calculate karna
    $base_price = (int)$_POST['d_price'];
    if($pkg == "Luxury") { $base_price += 2000; }
    $total = $base_price * $persons;

    // 4. Database mein INSERT karna
    // Note: Column names wahi rakhein jo my_bookings.php fetch kar raha hai
    $query = "INSERT INTO bookings (user_name, dest_name, persons, package_type, travel_date, total_price) 
              VALUES ('$user', '$destination', '$persons', '$pkg', '$t_date', '$total')";

    if(mysqli_query($conn, $query)) {
        // Success! Sidha "My Bookings" page par bhejein
        header("Location: my_bookings.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>