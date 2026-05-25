<?php 
include('db.php'); 
session_start(); 

$message = "";

// Feedback submit karne ka logic (Purana code)
if(isset($_POST['submit_feedback'])) {
    if(isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        $rating = mysqli_real_escape_string($conn, $_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        
        $query = "INSERT INTO feedback (username, rating, comment) VALUES ('$user', '$rating', '$comment')";
        if(mysqli_query($conn, $query)) {
            $message = "<div style='color:green; text-align:center; padding:10px; font-weight:600;'>Thank you! Your feedback has been submitted.</div>";
        } else {
            $message = "<div style='color:red; text-align:center; padding:10px;'>Error: Could not submit feedback.</div>";
        }
    } else {
        $message = "<div style='color:red; text-align:center; padding:10px;'>Please <a href='login.php'>Login</a> to submit your feedback.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback |MyTravel </title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --navy: #002366; --gold: #ffea00; }
        body { background: #f4f7f6; font-family: 'Poppins', sans-serif; margin:0; }

        /* --- INDEX WALA NAVIGATION CSS (Updated) --- */
        nav { 
            background: var(--navy); color: white; padding: 0 50px; 
            display: flex; justify-content: space-between; align-items: center; 
            position: sticky; top: 0; z-index: 1000; height: 75px;
        }
        .logo { font-size: 24px; font-weight: 700; }
        .nav-links { display: flex; align-items: center; height: 100%; }
        .nav-links a { color: white; text-decoration: none; padding: 0 15px; font-size: 14px; transition: 0.3s; font-weight: 500; }
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
        .mega-col a { color: #555 !important; padding: 5px 0 !important; display: block !important; font-size: 13px !important; text-decoration: none; border: none !important; height: auto !important; line-height: normal !important; text-align: left !important; }
        .mega-col a:hover { color: var(--navy) !important; font-weight: 600; }
        .dropdown:hover .dropdown-content { display: block; }

        .nav-btn-small {
            background: #27ae60; color: white !important; padding: 6px 15px !important;
            border-radius: 5px; font-size: 13px !important; font-weight: 600; transition: 0.3s;
            margin-left: 5px; text-decoration: none; display: inline-block;
            height: auto !important; line-height: normal !important;
        }
        

        /* --- FEEDBACK PAGE STYLING (Purana Style) --- */
        .feedback-container { max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 10px; margin: 20px 0; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 40px; color: #ddd; cursor: pointer; transition: 0.3s; }
        .star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label { color: var(--gold); }
        textarea { width: 100%; height: 120px; padding: 15px; border-radius: 10px; border: 1px solid #ddd; font-family: inherit; resize: none; box-sizing: border-box; }
        .btn-submit { background: var(--navy); color: white; border: none; padding: 15px 30px; border-radius: 50px; width: 100%; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .btn-submit:hover { background: #001a4d; transform: translateY(-2px); }

        .reviews-list { max-width: 800px; margin: 50px auto; padding: 0 20px; }
        .review-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; border-left: 5px solid var(--gold); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .review-user { font-weight: bold; color: var(--navy); font-size: 16px; margin-bottom: 5px; }
        .review-stars { color: var(--gold); margin-bottom: 10px; font-size: 18px; }
        .review-text { color: #555; font-size: 14px; line-height: 1.6; }
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

    <div class="feedback-container">
        <h2 style="text-align:center; color:#002366; margin-top:0;">We Value Your Feedback</h2>
        <p style="text-align:center; color:#666;">How was your experience with MyTravel?</p>
        <?php echo $message; ?>
        <form method="POST">
            <div class="star-rating">
                <input type="radio" name="rating" id="star5" value="5" required><label for="star5">★</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
            </div>
            <textarea name="comment" placeholder="Write your experience here..." required></textarea>
            <button type="submit" name="submit_feedback" class="btn-submit">Submit Review</button>
        </form>
    </div>

    <div class="reviews-list">
        <h2 style="text-align:center; color:#002366; margin-bottom: 30px;">Customer Reviews</h2>
        <?php 
        $all_reviews = mysqli_query($conn, "SELECT * FROM feedback ORDER BY id DESC");
        if(mysqli_num_rows($all_reviews) > 0) {
            while($rev = mysqli_fetch_assoc($all_reviews)) {
                echo "<div class='review-card'>";
                echo "<div class='review-user'>👤 ".htmlspecialchars($rev['username'])."</div>";
                echo "<div class='review-stars'>";
                for($i=1; $i<=5; $i++) {
                    echo ($i <= $rev['rating']) ? "★" : "☆";
                }
                echo "</div>";
                echo "<div class='review-text'>\"".htmlspecialchars($rev['comment'])."\"</div>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center; color:#999;'>No reviews yet. Be the first to review!</p>";
        }
        ?>
    </div>

    <footer style="background:#002366; color:#fff; text-align:center; padding:40px; margin-top:50px;">
        <p style="margin:0;">&copy; 2026 mytravel | Project by Pooja & Rohini</p>
    </footer>

</body>
</html>