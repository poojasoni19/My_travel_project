<?php 
include('db.php'); 
session_start(); 
if (!isset($_SESSION['reset_email'])) { header("Location: forgot_password.php"); exit(); }

$error = ""; $success = "";
if (isset($_POST['update_password'])) {
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = $_SESSION['reset_email'];

    if ($new_pass === $confirm_pass) {
        if (mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE email='$email'")) {
            $success = "Password updated! Redirecting...";
            unset($_SESSION['reset_email']);
            header("refresh:2;url=login.php");
        } else { $error = "Update failed!"; }
    } else { $error = "Passwords do not match!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | MY TRAVEL</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Exact same styling as login.php */
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
        .login-card { background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; width: 350px; text-align:center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #002366; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top:15px;}
        footer { background: #002366; color: white; padding: 20px; text-align: center; margin-top: auto; }
    </style>
</head>
<body>
    <nav>
        <div class="logo">🌏 MY TRAVEL</div>
        <div class="nav-links"><a href="index.php">Home</a>
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
            <a href="distance.php">Distance</a><a href="about.php">About Us</a><a href="feedback.php">Feedback</a>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2 style="color:#002366;">🆕 New Password</h2>
            <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
            <?php if($success) echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>
            <form method="POST" action="" style="text-align: left;">
                <label>New Password:</label>
                <input type="password" name="new_password" required placeholder="Enter new password">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required placeholder="Confirm new password">
                <button type="submit" name="update_password" class="btn">Update Password</button>
            </form>
        </div>
    </div>
    <footer><p>&copy; 2026 MY TRAVEL| Project Developed by Pooja & Rohini</p></footer>
</body>
</html>