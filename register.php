<?php
include('db.php');
session_start();
$message = "";

if(isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $email = mysqli_real_escape_string($conn, $_POST['u_email']);
    $pass = mysqli_real_escape_string($conn, $_POST['u_pass']);
    $phone = mysqli_real_escape_string($conn, $_POST['u_phone']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) {
        $message = "<div style='color:#721c24; background:#f8d7da; border:1px solid #f5c6cb; padding:8px; border-radius:4px; margin-bottom:15px; font-size:12px; text-align:center;'>Email already exists!</div>";
    } else {
        $query = "INSERT INTO users (username, email, password, phone, role) VALUES ('$name', '$email', '$pass', '$phone', 'user')";
        if(mysqli_query($conn, $query)) {
            $message = "<div style='color:#155724; background:#d4edda; border:1px solid #c3e6cb; padding:8px; border-radius:4px; margin-bottom:15px; font-size:12px; text-align:center;'>Success! <a href='login.php' style='font-weight:bold;'>Login Now</a></div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | MY TRAVEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* --- COPYING EXACT NAV CSS FROM INDEX.PHP --- */
        :root { --navy: #002366; --gold: #ffea00; --green: #27ae60; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            margin: 0; padding: 0; min-height: 100vh;
            display: flex; flex-direction: column;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; background-attachment: fixed;
        }

        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 50px; /* Exact Index.php Padding */
            background: var(--navy); 
            position: sticky; top: 0; z-index: 1000;
        }
        nav .logo { font-size: 20px; font-weight: 700; color: white; letter-spacing: 1px; }
        
        .nav-links { display: flex; align-items: center; gap: 20px; } /* Exact Index.php Gap */
        
        .nav-links a { 
            text-decoration: none; 
            color: white; 
            font-weight: 500; 
            font-size: 14px; /* Exact Index.php Font Size */
            transition: 0.3s; 
        }
        .nav-links a:hover { color: var(--gold); }

        /* --- EXACT MEGA MENU BOX (SMALLER AS REQUESTED) --- */
        .dropdown { position: static; } 
        .dropdown-content {
            display: none; position: absolute; left: 0; right: 0; background-color: #fff; width: 100%;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.15); z-index: 1000; border-top: 3px solid var(--gold); padding: 30px 0;
        }
        .mega-wrapper { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; padding: 0 40px; }
        .mega-col h3 { font-size: 15px; color: var(--navy); border-bottom: 2px solid var(--gold); padding-bottom: 10px; margin-bottom: 15px; text-transform: uppercase; }
        .mega-col a { color: #555 !important; padding: 5px 0 !important; display: block !important; font-size: 13px !important; text-decoration: none; border: none !important; }
        .mega-col a:hover { color: var(--navy) !important; font-weight: 600; }
        .dropdown:hover .dropdown-content { display: block; }

        /* --- EXACT BUTTON STYLES FROM INDEX.PHP --- */
        .nav-btn-small {
            background: var(--green); color: white !important; padding: 6px 15px !important;
            border-radius: 5px; font-size: 13px !important; font-weight: 600; transition: 0.3s;
            margin-left: 5px; text-decoration: none; display: inline-block;
        }
        .nav-btn-reg { background: var(--navy); border: 1px solid white; } 
        .nav-btn-small:hover { opacity: 0.9; transform: translateY(-2px); }

        /* --- REGISTRATION BOX UI --- */
        .main-container {
            flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 0;
        }
        .reg-box { 
            background: #ffffff; width: 90%; max-width: 330px; 
            padding: 25px; border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        .reg-box h2 { color: var(--navy); text-align: center; margin-bottom: 20px; font-size: 22px; font-weight: 600; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; font-size: 11px; text-transform: uppercase; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        .btn-reg { background: var(--navy); color: white; width: 100%; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; text-transform: uppercase; margin-top: 10px; }
        
        .links { text-align: center; margin-top: 15px; font-size: 13px; color: #666; }
        .brand { text-align: center; color: #d4af37; font-weight: bold; font-size: 15px; margin-bottom: 5px; display: block; }
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
                        echo "<div class='mega-col'><h3>$c</h3>";
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

<div class="main-container">
    <div class="reg-box">
        <span class="brand">🌏 MY TRAVEL</span>
        <h2>Sign Up</h2>
        
        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="u_name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="u_email" class="form-control" placeholder="Email ID" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" name="u_phone" class="form-control" placeholder="Mobile Number" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="u_pass" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="register" class="btn-reg">Register</button>
        </form>

        <div class="links">
            Already a member? <a href="login.php" style="color:var(--navy); font-weight:600; text-decoration:none;">Login</a><br><br>
            <a href="index.php" style="color: #999; text-decoration:none;">← Back to Home</a>
        </div>
    </div>
</div>

<footer style="background:#002366; color:#fff; text-align:center; padding:40px; margin-top:50px;">
    <p>&copy; 2026 MY TRAVEL | Project by Pooja & Rohini</p>
</footer>

</body>
</html>