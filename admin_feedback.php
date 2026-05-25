<?php 
include('db.php'); 
session_start(); 

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'><h1>Access Denied!</h1><a href='login.php'>Login</a></div>";
    exit();
}

// Delete Logic
if(isset($_GET['delete_id'])) {
    $del_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $query = "DELETE FROM feedback WHERE id = '$del_id'";
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Feedback deleted!'); window.location.href='admin_feedback.php';</script>";
        exit();
    }
}
$res = mysqli_query($conn, "SELECT * FROM feedback ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Feedbacks</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --admin-blue: #002366; --danger: #ff4d4d; --white: #ffffff; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; margin: 0; }
        
        nav { 
            background: var(--admin-blue); color: white; padding: 15px 40px; 
            display: flex; justify-content: space-between; align-items: center;
        }
        nav a { color: white; text-decoration: none; font-size: 14px; margin-left: 20px; }

        .admin-container { 
            width: 90%; max-width: 1200px; margin: 40px auto; 
            background: var(--white); padding: 30px; border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        /* Yahan Maine Semicolon Fix Kiya Hai */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            table-layout: fixed; 
        }

        th { background: #f1f5f9; color: #475569; padding: 15px; text-align: left; font-size: 12px; border-bottom: 2px solid #e2e8f0; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; word-wrap: break-word; }
        
        
        .rating-star { color: #f1c40f; }
        .btn-delete { 
            background: var(--danger); color: white; padding: 6px 12px; 
            border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: 600;
        }
        
    </style>
</head>
<body>

    <nav>
        <div style="font-size: 20px; font-weight: 600;">🌏 MyTRAVEL | Feedback</div>
        <div>
            <a href="admin_bookings.php">Bookings</a>
            <a href="admin_feedback.php" style="color:#ffea00;">User Feedbacks</a>
            <a href="logout.php" style="background:var(--danger); padding:5px 10px; border-radius:4px;">Logout</a>
        </div>
    </nav>

    <div class="admin-container">
        <h2 style="color:var(--admin-blue); margin:0;">User Reviews & Ratings</h2>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">User</th>
                    <th style="width: 15%;">Rating</th>
                    <th style="width: 50%;">Comment</th>
                    <th style="width: 15%; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td style="font-weight:600;"><?php echo strtoupper($row['username']); ?></td>
                    <td>
                        <span class="rating-star">
                            <?php for($i=1; $i<=5; $i++) echo ($i <= $row['rating']) ? "★" : "☆"; ?>
                        </span>
                    </td>
                    <td><?php echo $row['comment']; ?></td>
                    <td style="text-align: center;">
                        <a href="admin_feedback.php?delete_id=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer class="no-print" style="text-align:center; padding:30px; background:#002366; color:#94a3b8; font-size:12px;">
        &copy; 2026 MyTravel Admin Panel | Confidential Internal Document
    </footer>
</body>
</html>