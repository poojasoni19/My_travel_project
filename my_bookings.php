<?php 
include('db.php'); 
session_start(); 

// Login Check
if(!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

$u = $_SESSION['username']; // Logged in username
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | MyTravel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* --- GLOBAL HEADER & MEGA MENU STYLES --- */
        .dropdown { position: static; } 
        .dropdown-content {
            display: none; position: absolute; left: 0; right: 0; background-color: #fff; width: 100%;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.15); z-index: 1000; border-top: 3px solid #ffea00; padding: 30px 0;
        }
        .mega-wrapper { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; padding: 0 40px; }
        .mega-col h3 { font-size: 15px; color: #002366; border-bottom: 2px solid #ffea00; padding-bottom: 10px; margin-bottom: 15px; text-transform: uppercase; }
        .mega-col a { color: #555 !important; padding: 5px 0 !important; display: block !important; font-size: 13px !important; text-decoration: none; border: none !important; }
        .mega-col a:hover { color: #002366 !important; font-weight: 600; }
        .dropdown:hover .dropdown-content { display: block; }

        .nav-btn-small {
            background: #27ae60; color: white !important; padding: 6px 15px !important;
            border-radius: 5px; font-size: 13px !important; font-weight: 600; transition: 0.3s;
            margin-left: 5px; text-decoration: none; display: inline-block;
        }

        /* --- YOUR ORIGINAL BOOKING PAGE STYLES --- */
        :root { --navy: #002366; --gold: #ffea00; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; margin: 0; }
        
        .container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }
        .booking-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .page-title { color: var(--navy); border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px; background: #f8f9fa; color: #666; font-size: 13px; border-bottom: 2px solid #eee; }
        td { padding: 15px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        .price-tag { color: #27ae60; font-weight: bold; }
        .pkg-badge { background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }

        .no-data { text-align: center; padding: 50px; color: #888; }
        .btn-home { background: var(--navy); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>

<nav>
    <div class="logo">🌏 MyTravel</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <div class="dropdown">
            <a href="#" class="dropbtn">Places ▼</a>
            <div class="dropdown-content">
                <div class="mega-wrapper">
                    <?php 
                    $cats = ['Nature', 'Historical', 'Religious', 'Adventure'];
                    foreach($cats as $c) {
                        echo "<div class='mega-col'>";
                        echo "<h3>$c</h3>";
                        $sub_res = mysqli_query($conn, "SELECT id, name FROM destinations WHERE category='$c'");
                        while($sub_row = mysqli_fetch_assoc($sub_res)) {
                            $m_link = "details.php?id=".$sub_row['id'];
                            echo "<a href='$m_link'>".$sub_row['name']."</a>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <a href="distance.php">Distance</a>
        <a href="about.php">About Us</a>
        <a href="feedback.php">Feedback</a>
        
        <?php if(isset($_SESSION['username'])): ?>
            <a href="my_bookings.php" class="nav-btn-small">My Bookings</a>
            <a href="logout.php" style="color:#ff4d4d; margin-left:15px; font-size: 14px; font-weight:600;">Logout</a>
        <?php endif; ?>
    </div>
</nav> 

<div class="container">
    <div class="booking-card">
        <h2 class="page-title">Welcome, <?php echo htmlspecialchars($u); ?>!</h2>
        <p style="font-size: 13px; color: #666;">Showing your recent trip history:</p>

        <?php 
        $safe_u = mysqli_real_escape_string($conn, $u);
        $query = "SELECT * FROM bookings WHERE user_name = '$safe_u' ORDER BY id DESC";
        $res = mysqli_query($conn, $query);

        if($res && mysqli_num_rows($res) > 0) {
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Persons</th>
                        <th>Package</th>
                        <th>Travel Date</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($res)) { ?>
                    <tr>
                        <td style="font-weight:600;"><?php echo $row['dest_name']; ?></td>
                        <td>
    <?php 
        echo $row['persons'] . " Adults"; 
        if(isset($row['minors']) && $row['minors'] > 0) {
            echo ", " . $row['minors'] . " Minors";
        }
    ?>
</td>
                        <td><span class="pkg-badge"><?php echo $row['package_type']; ?></span></td>
                        <td><?php echo date('d M, Y', strtotime($row['travel_date'])); ?></td>
                        <td class="price-tag">₹<?php echo number_format($row['total_price'], 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php 
        } else { 
            echo "<div class='no-data'>";
            echo "<h3>No Bookings Found!</h3>";
            echo "<p>We checked our records, but found no bookings under the name '$u'.</p>";
            echo "<a href='index.php' class='btn-home'>Plan a Trip Now</a>";
            echo "</div>";
        } 
        ?>
    </div>
</div>

</body>
</html>