<?php 
include('db.php'); 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MyTravel </title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* --- HEADER & MEGA MENU CSS (EXACTLY FROM HOME) --- */
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
        .nav-btn-reg { background: #002366; } 

        /* --- ABOUT PAGE CONTENT STYLING --- */
        :root { --navy: #002366; --gold: #ffea00; --light-bg: #f8fafc; }
        
        .about-hero {
            background: linear-gradient(rgba(0,35,102,0.8), rgba(0,35,102,0.8)), url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?q=80');
            height: 350px; background-size: cover; background-position: center;
            display: flex; justify-content: center; align-items: center; color: white; text-align: center;
        }

        .container { max-width: 1100px; margin: 60px auto; padding: 0 25px; }
        .about-grid { display: flex; gap: 50px; align-items: center; margin-bottom: 80px; }
        .about-text { flex: 1.2; }
        .about-text h2 { color: var(--navy); font-size: 2.2rem; margin-bottom: 20px; border-left: 5px solid var(--gold); padding-left: 15px; }
        .about-text p { color: #555; line-height: 1.9; text-align: justify; margin-bottom: 20px; font-size: 16px; }
        .about-img { flex: 0.8; }
        .about-img img { width: 100%; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.15); border: 5px solid white; }

        .info-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin: 60px 0; text-align: center; }
        .info-box { background: white; padding: 40px 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .info-box:hover { transform: translateY(-10px); }
        .info-box h3 { color: var(--navy); margin: 15px 0 10px; }
        .info-box p { color: #777; font-size: 14px; margin: 0; }

        .contact-bar { 
            background: var(--navy); color: white; padding: 60px; border-radius: 20px; 
            display: flex; justify-content: space-around; align-items: center; text-align: center;
        }
        .contact-item h4 { color: var(--gold); margin-bottom: 10px; font-size: 18px; }
        .contact-item p { margin: 0; opacity: 0.9; }
    </style>
</head>
<body>

<nav>
    <div class="logo">🌏MY TRAVEL </div>
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
            <a href="my_bookings.php" class="nav-btn-small">My Bookings</a>
            <a href="logout.php" style="color:#ff4d4d; margin-left:15px; font-size: 14px; font-weight:600;">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn-small">Login</a>
            <a href="register.php" class="nav-btn-small nav-btn-reg">Register</a>
        <?php endif; ?>
    </div>
</nav> 

<div class="about-hero">
    <div>
        <h1 style="font-size: 3.5rem; margin:0; letter-spacing: 2px;">OUR STORY & MISSION</h1>
        <p style="font-size: 1.2rem; opacity: 0.9;">Professional Travel Excellence from Satna since 2026</p>
    </div>
</div>

<div class="container">
    <div class="about-grid">
        <div class="about-text">
            <h2>Discover MY TRAVEL </h2>
            <p><b>Travel World</b> was established with a singular vision: to simplify travel planning for the people of Satna. We understand that finding the perfect vacation can be stressful, especially when balancing comfort and cost. Our platform curates the finest, professional-grade fixed tour packages across India, ensuring every traveler gets a hassle-free, memorable experience.</p>
            <p>We pride ourselves on our meticulous pre-planning. From the moment you book, every detail of your journey—including premium transportation, A/C accommodation, and expert sightseeing tours—is managed. Our mission is to provide you with a fixed, worry-free plan, so your focus remains purely on making unforgettable memories.</p>
        </div>
        <div class="about-img">
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80" alt="Travel Destination">
        </div>
    </div>

    <div class="info-strip">
        <div class="info-box">
            <div style="font-size:40px;">🚆</div>
            <h3>Fixed Departures</h3>
            <p>All our tours depart from Satna Junction with confirmed A/C travel arrangements.</p>
        </div>
        <div class="info-box">
            <div style="font-size:40px;">🏨</div>
            <h3>Verified Stays</h3>
            <p>We partner only with top-rated 3-Star and 5-Star hotels for your total comfort.</p>
        </div>
        <div class="info-box">
            <div style="font-size:40px;">🛡️</div>
            <h3>24/7 Support</h3>
            <p>Our team is always available to assist you during your journey for any needs.</p>
        </div>
    </div>

    <div class="contact-bar">
        <div class="contact-item">
            <h4>VISIT US</h4>
            <p>Civil Lines, Near Circuit House,<br>Satna (M.P.) - 485001</p>
        </div>
        <div class="contact-item">
            <h4>CALL US</h4>
            <p>+91 98765 43210<br>+91 76722 12345</p>
        </div>
        <div class="contact-item">
            <h4>EMAIL US</h4>
            <p>info@mytravel.com<br>support@mytravel.com</p>
        </div>
    </div>
</div>

<footer style="background:#002366; color:#fff; text-align:center; padding:40px; margin-top:50px;">
    <p>&copy; 2026 mytravel | Project by Pooja & Rohini</p>
</footer>

</body>
</html>