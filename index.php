<?php 
include('db.php'); 
session_start(); 
$cat = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : 'Nature';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | MY TRAVEL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* --- MEGA MENU CSS --- */
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

        /* --- UI & BUTTONS --- */
        .nav-btn-small {
            background: #27ae60; color: white !important; padding: 6px 15px !important;
            border-radius: 5px; font-size: 13px !important; font-weight: 600; transition: 0.3s;
            margin-left: 5px; text-decoration: none; display: inline-block;
        }
        .nav-btn-reg { background: #002366; } 
        .nav-btn-small:hover { opacity: 0.9; transform: translateY(-2px); }

        .hero { 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80'); 
            height: 380px; background-size:cover; background-position:center;
            display:flex; flex-direction:column; justify-content:center; align-items:center; color:white; text-align:center;
        }

        .search-box {
            background: white; padding: 8px; border-radius: 50px; display: flex; width: 80%; max-width: 550px; margin-top: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .search-box input { border: none; outline: none; padding: 10px 20px; width: 100%; border-radius: 50px; font-size: 15px; color: #333; }

        /* GRID AND IMAGE SIZE UPDATED */
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 25px; 
            padding: 40px 50px; 
            background: #f4f7f6; 
        }
        
        .card { 
            background: white; border-radius: 12px; overflow: hidden; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.06); position: relative; 
            transition: 0.3s; cursor: pointer;
        }
        .card:hover { transform: translateY(-8px); box-shadow: 0 10px 25px rgba(0,0,0,0.12); }

        .extra-card { display: none !important; }

        .price-tag { font-weight: bold; color: #27ae60; font-size: 16px; }

        .action-container { text-align: center; padding: 20px 0 60px; background: #f4f7f6; width: 100%; clear: both; }
        .btn-action {
            padding: 12px 35px; background: #002366; color: white; border: none;
            border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15); transition: 0.3s;
        }
        .btn-action:hover { background: #001a4d; transform: scale(1.03); }
    </style>
</head>
<body>
   
<nav>
    <div class="logo">🌏 MY TRAVEL</div>
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
            
            <a href="my_bookings.php">My Bookings</a>
            <a href="logout.php" style="color:#ff4d4d; margin-left:15px; font-weight:600;">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn-small">Login</a>
            <a href="register.php" class="nav-btn-small nav-btn-reg">Register</a>
        <?php endif; ?>
    </div>
</nav> 

    <div class="hero">
        <h1 style="font-size: 2.8rem; font-weight:600; margin:0;">Experience the Beauty of India</h1>
        <p style="font-size: 1.1rem; margin-top:8px; opacity:0.9;">Professional handpicked packages from Satna</p>
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search destinations..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" style="background:#002366; color:white; border:none; padding:10px 25px; border-radius:50px; cursor:pointer;">Search</button>
        </form>
    </div>

    <div style="max-width:1200px; margin: 30px auto 0; padding: 0 50px;">
        <h2 style="color:#002366; font-size: 24px;">Our Featured Tour Packages</h2>
    </div>

    <div class="grid" id="destGrid">
        <?php
        $sql = $search ? "SELECT * FROM destinations WHERE name LIKE '%$search%'" : "SELECT * FROM destinations";
        $res = mysqli_query($conn, $sql);
        $total_rows = mysqli_num_rows($res);
        $i = 0;

        if($total_rows > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $i++;
                $card_class = ($i > 5) ? "card extra-card" : "card";
                $link = isset($_SESSION['username']) ? "details.php?id=".$row['id'] : "login.php?back_to=details.php?id=".$row['id'];
                $price = (!empty($row['price_economy'])) ? $row['price_economy'] : "4,999";
                $duration = (!empty($row['duration'])) ? $row['duration'] : "3 Nights / 4 Days";
                
                echo "
                <div class='$card_class' onclick=\"location.href='$link'\">
                    <div class='duration-tag' style='position:absolute; top:8px; left:8px; background:#ffea00; padding:3px 8px; font-size:10px; font-weight:600; border-radius:3px; z-index:5;'>$duration</div> 
                    <img src='".$row['image_url']."' style='width:100%; height:250px; object-fit:cover;'> 
                    <div style='padding:15px;'>
                        <h3 style='margin:0; color:#002366; font-size:17px;'>".$row['name']."</h3>
                        <p style='color:#666; font-size:12px; margin: 8px 0;'>Professional package included from Satna.</p>
                        <div style='display:flex; justify-content:space-between; align-items:center; border-top: 1px solid #eee; padding-top:10px;'>
                            <span class='price-tag'>₹".number_format($price)."/-</span>
                            <button style='padding:6px 12px; border-radius:5px; background:#002366; color:white; border:none; font-size:11px; cursor:pointer;'>Explore</button>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p style='padding:20px;'>No destinations found.</p>";
        }
        ?>
    </div>

    <?php if($total_rows > 5): ?>
    <div class="action-container">
        <button id="toggleBtn" class="btn-action" onclick="toggleDestinations()">Show More Destinations</button>
    </div>
    <?php endif; ?>

    <script>
    var isExpanded = false; 

    function toggleDestinations() {
        var extras = document.querySelectorAll('.extra-card');
        var btn = document.getElementById('toggleBtn');

        if (!isExpanded) {
            extras.forEach(function(card) {
                card.style.setProperty('display', 'block', 'important'); 
            });
            btn.innerHTML = "Show Less Destinations "; 
            isExpanded = true;
        } else {
            extras.forEach(function(card) {
                card.style.setProperty('display', 'none', 'important'); 
            });
            btn.innerHTML = "Show More Destinations "; 
            isExpanded = false;
            document.getElementById('destGrid').scrollIntoView({ behavior: 'smooth' });
        }
    }
    </script>
     <footer style="background:#002366; color:#fff; text-align:center; padding:40px; margin-top:50px;">
        <p>&copy; 2026 MY TRAVEL | Project by Pooja & Rohini</p>
    </footer>

</body>
</html>