<?php 
include('db.php'); 
session_start(); 
$error = "";

// Redirect logic (Kept exactly as per your original code)
if(isset($_GET['redirect'])) {
    $redirect_to = $_GET['redirect'];
} elseif(isset($_GET['back_to'])) {
    $redirect_to = $_GET['back_to'];
} else {
    $redirect_to = 'index.php';
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($email) && !empty($password)) {
        
        // --- ADMIN STATIC CHECK ---
        // Static login for Admin without database modification
        if ($email == 'admin@gmail.com' && $password == 'admin123') {
            $_SESSION['username'] = 'Admin';
            $_SESSION['role'] = 'admin';
            header("Location: admin_bookings.php"); 
            exit();
        } 
        
        // --- DATABASE CHECK (For Normal Users) ---
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $user_data['username']; 
            $_SESSION['role'] = 'user'; // Setting role for normal user
            header("Location: " . $redirect_to); 
            exit();
        } else {
            $error = "Invalid Email or Password!";
        }
    } else {
        $error = "Please enter email and password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | MY TRAVEL</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
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

        body { 
            background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500&auto=format&fit=crop&q=60'); 
            background-size: cover; 
            background-position: center;
            background-attachment: fixed;
            font-family: sans-serif; 
            margin: 0; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .login-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 0;
        }
        .login-card { 
            background: rgba(255,255,255,0.95); 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); 
            width: 350px; 
            text-align:center;
        }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #002366; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top:15px;}
        
        .nav-btn-small {
            background: #27ae60;
            color: white !important;
            padding: 6px 15px !important;
            border-radius: 5px;
            font-size: 13px !important;
            font-weight: 600;
            text-decoration: none;
            margin-left: 5px;
            display: inline-block;
        }
        .nav-btn-reg { background: #002366; } 

        footer {
            background: #002366;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: auto;
        }
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
                                echo "<a href='details.php?id=".$sub_row['id']."'>".$sub_row['name']."</a>";
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
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="admin_bookings.php" class="nav-btn-small" style="background:#ffea00; color:#000 !important;">Admin Panel</a>
                <?php else: ?>
                    <a href="my_bookings.php" class="nav-btn-small">My Bookings</a>
                <?php endif; ?>
                <a href="logout.php" style="color:#ff4d4d; margin-left:15px; font-size: 14px; font-weight:600;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-btn-small">Login</a>
                <a href="register.php" class="nav-btn-small nav-btn-reg">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2 style="color:#002366;">🌏 Traveler Login</h2>
            <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
            
            <form method="POST" action="login.php?redirect=<?php echo urlencode($redirect_to); ?>" style="text-align: left;">
                <label> Email Address:</label>
                <input type="email" name="email" required placeholder="Enter your email">
                
                <label> Password:</label>
                <input type="password" name="password" required placeholder="Enter your password">
                
                <button type="submit" name="login" class="btn">Login Now</button>

<div style="text-align: right; margin-top: 10px;">
    <a href="forgot_password.php" style="color: #002366; font-size: 13px; text-decoration: none; font-weight: 600;">Forgot Password?</a>
</div>

            </form>

            <p style="margin-top: 20px; font-size: 14px; color: #555;">
                Don't have an account? <a href="register.php" style="color: #002366; font-weight: bold; text-decoration: none;">Register Here</a>
            </p>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 MY TRAVEL| Project Developed by Pooja & Rohini</p>
    </footer>

</body>
</html>