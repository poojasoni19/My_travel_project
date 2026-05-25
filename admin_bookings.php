<?php 
include('db.php'); 
session_start(); 

// Booking Delete Logic - Fixed & Professional
if(isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = mysqli_query($conn, "DELETE FROM bookings WHERE id = '$id'");
    
    if($delete_query) {
        echo "<script>
                alert('Booking record has been successfully removed.'); 
                window.location.href='admin_bookings.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: Unable to delete the record.');
              </script>";
    }
}

// Strict Admin Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h1 style='color:red;'>Access Denied!</h1>
            <p>You do not have administrative privileges to access this secure dashboard.</p>
            <a href='login.php'>Login as Admin</a>
          </div>"; 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management Dashboard | MyTravel </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-blue: #002366;
            --success: #27ae60;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --danger: #ff4d4d; /* Cancel button ke liye red color */
        }
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); margin: 0; color: #333; }
        
        nav { 
            background: var(--admin-blue); 
            color: white; 
            padding: 15px 40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        nav a { color: white; text-decoration: none; font-size: 14px; font-weight: 500; transition: 0.3s; }
        nav a:hover { color: #ffea00; }

        .container { 
            width: 95%; 
            max-width: 1300px;
            margin: 40px auto; 
            background: var(--white); 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid var(--admin-blue);
        }
        .stat-card h4 { margin: 0; font-size: 12px; color: #64748b; text-transform: uppercase; }
        .stat-card p { margin: 5px 0 0; font-size: 20px; font-weight: 600; color: var(--admin-blue); }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background: #f1f5f9; 
            color: #475569; 
            padding: 15px; 
            text-align: left; 
            text-transform: uppercase; 
            font-size: 12px; 
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
        }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #1e293b; }
        tr:hover { background: #f8fafc; }

        .pkg-badge {
            background: #e2e8f0;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Cancel Button Styling */
        .btn-cancel {
            background: var(--danger);
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-cancel:hover { background: #cc0000; }

        .print-btn { 
            background: var(--success); 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600; 
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .print-btn:hover { background: #1e8449; transform: translateY(-2px); }

        @media print { 
            .no-print { display: none !important; } 
            .container { box-shadow: none; width: 100%; margin: 0; padding: 0; }
            body { background: white; }
            th { background: #eee !important; color: black !important; }
        }
    </style>
</head>
<body>
    <nav class="no-print">
        <div style="font-size: 20px; font-weight: 600; letter-spacing: 1px;">🌏 MYTRAVEL <span style="font-weight:300; font-size: 14px;">| Admin Central</span></div>
        <div>
            <a href="admin_feedback.php" style="margin-right:25px; color:#ffea00;">User Feedbacks</a> 
            <a href="logout.php" style="background:#ff4d4d; padding:8px 15px; border-radius:5px;">System Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <div>
                <h2 style="margin:0; color: var(--admin-blue);">Master Reservation Records</h2>
                <p style="margin:5px 0 0; color: #64748b; font-size: 14px;">Review and manage all customer bookings globally.</p>
            </div>
            <button class="print-btn no-print" onclick="window.print()">
                <span>🖨️</span> Generate Official Report
            </button>
        </div>

        <?php 
        $total_res = mysqli_query($conn, "SELECT COUNT(*) as total, SUM(REPLACE(REPLACE(total_price, '₹', ''), ',', '')) as revenue FROM bookings");
        $stats = mysqli_fetch_assoc($total_res);
        ?>
        <div class="stats-bar no-print">
            <div class="stat-card">
                <h4>Total Bookings</h4>
                <p><?php echo $stats['total']; ?></p>
            </div>
            <div class="stat-card">
                <h4>Total Revenue</h4>
                <p>₹<?php echo number_format($stats['revenue']); ?></p>
            </div>
            <div class="stat-card">
                <h4>Status</h4>
                <p style="color:var(--success);">System Active</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Destination</th>
                    <th>Contact Info</th>
                    <th>Group Details</th>
                    <th>Package Type</th>
                    <th>Travel Date</th>
                    <th>Total Fare</th>
                    <th class="no-print">Action</th> </tr>
            </thead>
            <tbody>
            <?php 
            $res = mysqli_query($conn, "SELECT * FROM bookings ORDER BY id DESC");
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $pkg = isset($row['package_type']) ? $row['package_type'] : "Fixed Plan";
                    $adult_val = $row['persons']; 
                    $minor_val = $row['minors'];
                    $group_display = $adult_val . " Adults, " . $minor_val . " Minors";

                    echo "<tr>
                            <td><b style='color:var(--admin-blue);'>".strtoupper($row['user_name'])."</b></td>
                            <td>".$row['dest_name']."</td>
                            <td>".$row['phone']."</td>
                            <td>".$group_display."</td>
                            <td><span class='pkg-badge'>".$pkg."</span></td>
                            <td>".date('d M, Y', strtotime($row['travel_date']))."</td>
                            <td style='color:var(--success); font-weight:bold;'>₹".$row['total_price']."</td>
                            <td class='no-print'>
    <a href='?delete_id=".$row['id']."' 
       class='btn-cancel' 
       onclick=\"return confirm('Are you sure you want to cancel this booking?')\">
       Cancel
    </a>
</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center; padding:50px; color:#94a3b8;'>No booking records found in the database.</td></tr>";
            }
            ?>
            </tbody>        
        </table>
    </div>

    <footer class="no-print" style="text-align:center; padding:30px; background:#002366; color:#94a3b8; font-size:12px;">
        &copy; 2026 MyTravel Admin Panel | Confidential Internal Document
    </footer>
</body>
</html>