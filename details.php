<?php 
include('db.php'); 
session_start();

if(!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = mysqli_real_escape_string($conn, $_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM destinations WHERE id='$id'");
$p = mysqli_fetch_assoc($res); 

if(!$p) { die("Destination Not Found!"); }

$show_ticket = false;
$error_msg = ""; 

if(isset($_POST['request_booking'])) {
    if(!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
    
    $u_name = $_SESSION['username'];
    $u_pkg = $_POST['p_package'];
    $u_date = $_POST['p_date'];
    $u_adults = (int)$_POST['p_adults'];
    $u_minors = (int)$_POST['p_minors']; 
    $u_phone = $_POST['p_phone'];

    if(strlen($u_phone) != 10) {
        $error_msg = "Mobile number 10 digits ka hi hona chahiye!";
    } 
    else {
        $check_booking = mysqli_query($conn, "SELECT * FROM bookings WHERE user_name='$u_name' AND travel_date='$u_date'");
        
        if(mysqli_num_rows($check_booking) > 0) {
            $old_b = mysqli_fetch_assoc($check_booking);
            $old_id = $old_b['booking_id'];
            // Same error as your image with a clickable link
            $error_msg = "Aapne is date par pehle se hi ek booking ki hui hai. Purani cancel karein ya date badlein! <br><br> 
                         <a href='cancel_and_rebook.php?old_id=$old_id' style='color:yellow; font-weight:bold;'>[ Click Here to Cancel Old & Rebook ]</a>";
            
            $_SESSION['temp_booking'] = $_POST;
            $_SESSION['temp_dest'] = $p['name'];
        } else {
            $pkg_price = ($u_pkg == 'Economy') ? $p['price_economy'] : (($u_pkg == 'Premium') ? $p['price_premium'] : $p['price_luxury']);
            $total_fare = $pkg_price * ($u_adults + $u_minors);
            $booking_id = "TW-" . rand(100000, 999999);
            
            $dest_name = $p['name'];
            $query = "INSERT INTO bookings (booking_id, user_name, dest_name, travel_date, persons, minors, package_type, total_price, phone) 
                      VALUES ('$booking_id', '$u_name', '$dest_name', '$u_date', '$u_adults', '$u_minors', '$u_pkg', '$total_fare', '$u_phone')";
            
            if(mysqli_query($conn, $query)) {
                $show_ticket = true;
            } else {
                $error_msg = "Database Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $p['name']; ?> | Complete Trip Details</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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

        :root { --navy: #002366; --gold: #d4af37; --bg: #f8fafc; --success: #27ae60; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); color: #333; margin: 0; }
        .main-wrapper { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .hero-banner { height: 450px; border-radius: 20px; overflow: hidden; position: relative; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .hero-banner img { width: 100%; height: 100%; object-fit: cover; }
        .hero-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.9)); padding: 40px; color: white; }
        .comparison-section { margin-top: 40px; }
        .comp-table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .comp-table th { background: var(--navy); color: white; padding: 20px; text-align: center; font-size: 18px; width: 25%; }
        .comp-table td { padding: 20px; border: 1px solid #f1f5f9; vertical-align: top; }
        .feature-head { background: #f8fafc; font-weight: 600; color: var(--navy); text-align: left !important; width: 20% !important; }
        .price-text { font-size: 24px; font-weight: bold; color: var(--success); display: block; margin-top: 5px; }
        .detail-block { background: white; border-radius: 15px; padding: 40px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
        .section-title { font-size: 24px; color: var(--navy); border-left: 5px solid var(--gold); padding-left: 15px; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; }
        .sticky-booking { background: var(--navy); color: white; padding: 30px; border-radius: 15px; position: sticky; top: 20px; }
        .form-control { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: none; }
        .btn-book { background: var(--gold); color: black; padding: 15px; border: none; border-radius: 8px; width: 100%; font-weight: bold; cursor: pointer; margin-top: 15px; font-size: 16px; }
        .ticket-wrapper { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; justify-content: center; align-items: center; }
        .boarding-pass { width: 750px; background: #fff; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; font-family: 'Courier New', Courier, monospace; }
        .t-header { background: #002366; color: #fff; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        .t-content { padding: 25px; border-top: 2px dashed #002366; position: relative; }
        .t-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .t-label { color: #888; font-size: 12px; text-transform: uppercase; }
        .t-value { font-size: 16px; font-weight: bold; color: #000; margin-bottom: 10px; }
        .barcode { text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }
        .verified-stamp { position: absolute; right: 30px; bottom: 60px; border: 3px solid #27ae60; color: #27ae60; padding: 5px 15px; border-radius: 5px; transform: rotate(-15deg); font-weight: bold; font-size: 20px; opacity: 0.7; }
    </style>
</head>
<body>
    <nav>
    <div class="logo">🌏 TRAVEL WORLD</div>
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
        <?php if(isset($_SESSION['username'])): ?>
            <a href="feedback.php">Feedback</a>
            <a href="my_bookings.php">My Bookings</a>
            <a href="logout.php" style="color:#ff4d4d; margin-left:15px; font-weight:600;">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn-small">Login</a>
            <a href="register.php" class="nav-btn-small nav-btn-reg">Register</a>
        <?php endif; ?>
    </div>
</nav> 

<?php if($show_ticket): ?>
<div class="ticket-wrapper">
    <div class="boarding-pass">
        <div class="t-header"><h2>🌏 TRAVEL WORLD</h2></div>
        <div class="t-content">
            <div class="verified-stamp">VERIFIED</div>
            <div class="t-grid">
                <div>
                    <div class="t-label">Passenger</div>
                    <div class="t-value"><?php echo strtoupper($u_name); ?></div>
                    <div class="t-label">Destination</div>
                    <div class="t-value"><?php echo $p['name']; ?></div>
                </div>
                <div style="text-align: right;">
                    <div class="t-label">Booking ID</div>
                    <div class="t-value">#<?php echo $booking_id; ?></div>
                    <div class="t-label">Travel Date</div>
                    <div class="t-value"><?php echo date("d-m-Y", strtotime($u_date)); ?></div>
                </div>
            </div>
            <div class="barcode">
                <button onclick="window.print()">Print</button>
                <button onclick="window.location.href='index.php'">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="main-wrapper">
    <div class="hero-banner">
        <img src="<?php echo $p['image_url']; ?>" alt="Banner">
        <div class="hero-overlay">
            <h1><?php echo $p['name']; ?> | Fixed Professional Tour</h1>
            <p>Package ID: <?php echo $p['pkg_id']; ?> | Duration: 2 Nights / 3 Days</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <div class="content-area">
            <div class="detail-block">
                <h2 class="section-title">Package Overview</h2>
                <p><?php echo $p['description']; ?></p>
            </div>

            <div class="detail-block">
                <h2 class="section-title">Compare Facilities</h2>
                <table class="comp-table">
                    <thead>
                        <tr>
                            <th class="feature-head">Service</th>
                            <th>Economy <span class="price-text">₹<?php echo number_format($p['price_economy']); ?></span></th>
                            <th>Premium <span class="price-text">₹<?php echo number_format($p['price_premium']); ?></span></th>
                            <th>Luxury <span class="price-text">₹<?php echo number_format($p['price_luxury']); ?></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="feature-head">Accommodation</td><td>2-Star Guesthouse</td><td>3-Star AC Hotel</td><td>5-Star Luxury Resort</td></tr>
                        <tr><td class="feature-head">Transport</td><td>Sleeper Train/Bus</td><td>3-Tier AC Train</td><td>Private SUV / Flight</td></tr>
                        <tr><td class="feature-head">Meals</td><td>Only Breakfast</td><td>Breakfast & Dinner</td><td>All Meals (Buffet)</td></tr>
                        <tr><td class="feature-head">Sightseeing</td><td>Shared Group Tour</td><td>Private Sedan</td><td>Luxury SUV + Guide</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="detail-block">
                <h2 class="section-title">Day-Wise Plan</h2>
                <div style="padding-left: 20px; border-left: 3px solid var(--gold);">
                    <h4 style="color:var(--navy);">Day 1: Arrival & Local Exploration</h4><p><?php echo $p['itinerary_day1']; ?></p><hr>
                    <h4 style="color:var(--navy);">Day 2: Full Sightseeing Tour</h4><p><?php echo $p['itinerary_day2']; ?></p><hr>
                    <h4 style="color:var(--navy);">Day 3: Final Departure</h4><p><?php echo $p['itinerary_day3']; ?></p>
                </div>
            </div>

            <div class="detail-block">
                <h2 class="section-title">Attractions & Things to do</h2>
                <p><strong>Main Highlights:</strong> <?php echo $p['attractions']; ?></p>
                <p><strong>Must Try Activities:</strong> <?php echo $p['things_to_do']; ?></p>
                <div style="background:#fff7e6; padding:20px; border-radius:10px; margin-top:20px;">
                    <strong>Terms & Policies:</strong> <?php echo $p['terms']; ?>
                </div>
            </div>
        </div>

        <div class="sidebar-area">
            <div class="sticky-booking">
                <h3>Book This Trip</h3>
                <form method="POST">
                    <?php if($error_msg != ""): ?>
                        <div style="background: #ff4d4d; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 13px; border-left: 4px solid #b30000;">
                            <?php echo $error_msg; ?>
                        </div>
                    <?php endif; ?>
                    <label>Package Category</label>
                    <select name="p_package" class="form-control">
                        <option value="Economy">Economy Saver</option>
                        <option value="Premium" selected>Premium Comfort</option>
                        <option value="Luxury">Elite Luxury</option>
                    </select>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;"><label>Adults</label><input type="number" name="p_adults" value="1" min="1" class="form-control"></div>
                        <div style="flex:1;"><label>Minors</label><input type="number" name="p_minors" value="0" min="0" class="form-control"></div>
                    </div>
                    <label>Travel Date</label>
                    <input type="date" name="p_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                    <label>Mobile Number</label>
                    <input type="tel" name="p_phone" placeholder="10-digit number" class="form-control" 
                           pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    <button type="submit" name="request_booking" class="btn-book">CONFIRM & GET TICKET</button>
                </form>
            </div>
        </div>
    </div>
</div>
<footer style="background:var(--navy); color:white; text-align:center; padding:50px; margin-top:50px;">
    <p>&copy; 2026 mytravel | Project by Pooja & Rohini</p>
</footer>
</body>
</html>