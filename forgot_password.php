<?php 
include('db.php'); 
session_start(); 
$error = "";

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['reset_email'] = $email;
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "This email is not registered!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | MY TRAVEL</title>
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
            background-size: cover; background-position: center; background-attachment: fixed;
            font-family: sans-serif; margin: 0; display: flex; flex-direction: column; min-height: 100vh;
        }
        .login-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 50px 0; }
        .login-card { 
            background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 350px; text-align:center;
        }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #002366; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top:15px;}
        .nav-btn-small { background: #27ae60; color: white !important; padding: 6px 15px !important; border-radius: 5px; font-size: 13px !important; font-weight: 600; text-decoration: none; margin-left: 5px; display: inline-block; }
        footer { background: #002366; color: white; padding: 20px; text-align: center; margin-top: auto; }
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
            <a href="login.php" class="nav-btn-small">Login</a>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2 style="color:#002366;">🔒 Forgot Password</h2>
            <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="" style="text-align: left;">
                <label>Registered Email:</label>
                <input type="email" name="email" required placeholder="Enter your email">
                <button type="submit" name="reset_request" class="btn">Verify Email</button>
            </form>
            <p style="margin-top: 20px; font-size: 14px;"><a href="login.php" style="color: #002366; text-decoration: none; font-weight:bold;">Back to Login</a></p>
        </div>
    </div>
    <footer><p>&copy; 2026 MY TRAVEL| Project Developed by Pooja & Rohini</p></footer>
</body>
</html>