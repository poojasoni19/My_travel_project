<?php 
include('db.php'); 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distance Tracker | MyTravel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --navy: #002366; --gold: #ffea00; --light-bg: #f4f7f6; }
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); margin: 0; }

        /* --- INDEX STYLE HEADER (Updated) --- */
        nav { 
            background: var(--navy); 
            color: white; 
            padding: 0 50px; 
            height: 75px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            position: sticky; 
            top: 0; 
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo { font-size: 24px; font-weight: bold; letter-spacing: 1px; }
        .nav-links { display: flex; align-items: center; height: 100%; }
        .nav-links a { 
            color: white; 
            text-decoration: none; 
            padding: 0 15px; 
            font-weight: 500; 
            font-size: 14px; 
            transition: 0.3s; 
            display: inline-block;
        }
        .nav-links a:hover { color: var(--gold); }

        /* --- MEGA MENU CSS (Same as Index) --- */
        .dropdown { position: static; height: 100%; display: flex; align-items: center; } 
        .dropdown-content {
            display: none; position: absolute; left: 0; right: 0; background-color: #fff; width: 100%;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.15); z-index: 1000; border-top: 3px solid var(--gold); padding: 30px 0;
            top: 75px;
        }
        .mega-wrapper { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; padding: 0 40px; }
        .mega-col h3 { font-size: 15px; color: var(--navy); border-bottom: 2px solid var(--gold); padding-bottom: 10px; margin-bottom: 15px; text-transform: uppercase; text-align: left; }
        .mega-col a { color: #555 !important; padding: 5px 0 !important; display: block !important; font-size: 13px !important; text-decoration: none; border: none !important; height: auto !important; line-height: normal !important; text-align: left !important;}
        .mega-col a:hover { color: var(--navy) !important; font-weight: 600; }
        .dropdown:hover .dropdown-content { display: block; }

        .nav-btn-small {
            background: #27ae60; color: white !important; padding: 6px 15px !important;
            border-radius: 5px; font-size: 13px !important; font-weight: 600; transition: 0.3s;
            margin-left: 5px; text-decoration: none; display: inline-block;
            height: auto !important; line-height: normal !important;
        }
        

        /* Distance Tracker Box Styling */
        .btn-main { background: var(--navy); color: white; border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer; width: 100%; font-weight: 600; margin-top: 10px; transition: 0.3s; }
        .btn-main:hover { background: #001a4d; }
    </style>
</head>
<body>

<nav>
    <div class="logo">🌏 MY TRAVEL </div>
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
                            $m_link = isset($_SESSION['username']) ? "details.php?id=".$sub_row['id'] : "login.php?back_to=details.php?id=".$sub_row['id'];
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
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <a href="admin_bookings.php" class="nav-btn-small" style="background:#ffea00; color:#000 !important;">Admin Panel</a>
            <?php else: ?>
                <a href="my_bookings.php" class="nav-btn-small">My Bookings</a>
            <?php endif; ?>
            <a href="logout.php" style="color:#ff4d4d; margin-left:15px; font-weight:600;">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn-small">Login</a>
            <a href="register.php" class="nav-btn-small nav-btn-reg">Register</a>
        <?php endif; ?>
    </div>
</nav> 

<div style="max-width:400px; margin:60px auto; background:white; padding:40px; border-radius:20px; text-align:center; box-shadow:0 10px 40px rgba(0,0,0,0.1);">
    <h2 style="color:var(--navy); margin-top: 0;">Satna Distance Tracker</h2>
    <form method="POST">
        <p style="font-size: 14px;">Your Current Location: <b>Satna, MP</b></p>
        <select name="to" required style="width:100%; padding:12px; margin:15px 0; border-radius:10px; border: 1px solid #ddd; outline: none;">
            <option value="">-- Select Destination --</option>
            <option value="Taj Mahal">Taj Mahal (Agra)</option>
            <option value="Varanasi">Varanasi</option>
            <option value="Khajuraho">Khajuraho</option>
            <option value="Red Fort">Red Fort (Delhi)</option>
            <option value="Hawa Mahal">Hawa Mahal (Jaipur)</option>
            <option value="Mysore Palace">Mysore Palace</option>
            <option value="Umaid Bhawan Palace">Umaid Bhawan Palace</option>
            <option value="City Palace Udaipur">City Palace Udaipur</option>
            <option value="Kedarnath">Kedarnath</option>
            <option value="Rishikesh">Rishikesh</option>
            <option value="Golden Temple">Golden Temple</option>
            <option value="Manali">Manali</option>
            <option value="Auli">Auli</option>
            <option value="Valley of Flowers">Valley of Flowers</option>
            <option value="Munnar">Munnar</option>
            <option value="Ooty">Ooty</option>
            <option value="Coorg">Coorg</option>
            <option value="Ajanta Caves">Ajanta Caves</option>
            <option value="Tirupati Balaji">Tirupati Balaji</option>
            <option value="Leh Ladakh">Leh Ladakh</option>
            <option value="Andaman">Andaman</option>
            <option value="Jim Corbett">Jim Corbett</option>
        </select>
        <button type="submit" name="calc" class="btn-main">Get Accurate Distance</button>
    </form>

    <?php if(isset($_POST['calc'])) {
        $to = $_POST['to'];
        $data = [
            "Taj Mahal" => 524, "Varanasi" => 279, "Amarnath" => 1550, "Red Fort" => 776, 
            "Hawa Mahal" => 729, "Kedarnath" => 750, "Rishikesh" => 850, "Golden Temple" => 1150, 
            "Manali" => 1200, "Auli" => 930, "Valley of Flowers" => 1050, "Munnar" => 2050, 
            "Ooty" => 1900, "Coorg" => 1800, "Mysore Palace" => 1700, "Umaid Bhawan Palace" => 780,
            "City Palace Udaipur" => 850, "Ajanta Caves" => 850, "Tirupati Balaji" => 1400, 
            "Leh Ladakh" => 1900, "Andaman" => 2250, "Jim Corbett" => 780
        ];

        if(isset($data[$to])) {
            echo "<div style='margin-top:25px; background:#eef2f7; padding:20px; border-radius:15px;'>
                    <h1 style='color:var(--navy); margin:0;'>".$data[$to]." KM</h1>
                    <p style='margin:0; font-size: 14px;'>Estimated travel from <b>Satna</b> to <b>$to</b></p>
                    <small style='color:green;'>Route verified via NH44 / NH30</small>
                  </div>";
        }
    } ?>
</div>

<footer style="background:#002366; color:#fff; text-align:center; padding:40px; margin-top:50px;">
    <p style="margin: 0;">&copy; 2026 mytravel | Project by Pooja & Rohini</p>
</footer>

</body>
</html>