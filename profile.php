<?php
session_start();
require_once 'db_connect.php';
require_once 'lang_config.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = getDBConnection();
$user = null;
$orders = [];
$total_co2 = 0;

if ($db) {
    // Fetch User Details
    $usersCollection = $db->users;
    $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

    // Fetch Order History
    $ordersCollection = $db->orders;
    $cursor = $ordersCollection->find(
        ['user_id' => $_SESSION['user_id']],
        ['sort' => ['order_date' => -1]] 
    );

    foreach ($cursor as $doc) {
        $order = (array)$doc;
        // Calculate total lifetime impact
        if(isset($order['shipping']['co2_cost'])) {
             // Summing estimated savings
             if (isset($order['items'])) {
                $total_co2 += 0.5 * count($order['items']);
             }
        }
        $orders[] = $order;
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #fdfbf7; color: #333; }
        
        .profile-container { max-width: 1200px; margin: 60px auto; padding: 0 40px; display: grid; grid-template-columns: 300px 1fr; gap: 60px; }
        
        /* SIDEBAR (Centered & Fixed) */
        .profile-sidebar { 
            padding-right: 40px;
            display: flex; flex-direction: column; align-items: center; text-align: center;
            border-right: 1px solid #eee;
        }
        
        .user-avatar {
            width: 120px; height: 120px; background-color: #1a4d2e; color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif; font-size: 48px; margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(26, 77, 46, 0.2);
        }
        
        .user-name { font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 5px; color: #000; }
        .user-email { color: #666; font-size: 14px; margin-bottom: 40px; }
        
        .stat-box { 
            background: #fff; padding: 25px; border-radius: 12px; margin-bottom: 20px; 
            width: 100%; box-sizing: border-box; border: 1px solid #f0f0f0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        }
        .stat-num { font-family: 'Playfair Display', serif; font-size: 36px; color: #1a4d2e; display: block; line-height: 1; margin-bottom: 5px; }
        .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #999; font-weight: 700; }

        .btn-logout {
            display: block; width: 100%; padding: 15px; border: 1px solid #333; background: white;
            color: #333; text-align: center; text-decoration: none; font-weight: bold; font-size: 13px;
            text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; margin-top: 40px;
        }
        .btn-logout:hover { background: #000; color: white; }

        /* MAIN CONTENT */
        .section-title {
            font-family: 'Playfair Display', serif; font-size: 32px; margin: 0 0 40px 0; color: #000; 
        }

        /* TABLE STYLING */
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0 15px; /* Space between rows */
        }
        
        th { 
            text-align: left; padding: 0 20px 15px 20px; color: #999; font-size: 11px; 
            text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; 
        }
        
        tr.order-row { 
            background: white; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.02); /* Static shadow, no hover movement */
        }

        td { 
            padding: 25px 20px; 
            vertical-align: top; /* Align text to top */
            border-top: 1px solid #f9f9f9;
            border-bottom: 1px solid #f9f9f9;
            font-size: 15px; color: #333;
        }
        
        td:first-child { border-left: 1px solid #f9f9f9; border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        td:last-child { border-right: 1px solid #f9f9f9; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        
        .status-badge {
            background: #f0fdf4; color: #166534; padding: 6px 12px; border-radius: 4px; 
            font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block;
        }
        
        .items-text { line-height: 1.6; }
        .items-text div { margin-bottom: 4px; }
        
        .date-text { font-weight: 600; color: #555; }

        .total-price { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: bold; color: #1a4d2e; }

        @media (max-width: 900px) {
            .profile-container { grid-template-columns: 1fr; border: none; }
            .profile-sidebar { border-right: none; border-bottom: 1px solid #eee; padding-bottom: 40px; padding-right: 0; }
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="profile-container">
    
    <!-- Sidebar -->
    <div class="profile-sidebar">
        <div class="user-avatar">
            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
        </div>
        <h1 class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p class="user-email"><?php 
            echo isset($user['email']) ? $user['email'] : 'user@example.com'; 
        ?></p>
        
        <div class="stat-box">
            <span class="stat-num">-<?php echo number_format($total_co2, 1); ?>kg</span>
            <span class="stat-label">Carbon Saved</span>
        </div>
        
        <div class="stat-box">
            <span class="stat-num"><?php echo count($orders); ?></span>
            <span class="stat-label">Total Orders</span>
        </div>

        <a href="logout.php" class="btn-logout">Sign Out</a>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
        <h2 class="section-title">Order History</h2>
        
        <?php if (empty($orders)): ?>
            <div style="background: #fff; padding: 60px; border-radius: 10px; text-align: center; border: 1px solid #eee;">
                <i class="far fa-shopping-bag" style="font-size: 40px; color: #ddd; margin-bottom: 20px;"></i>
                <p style="color: #666; margin-bottom: 20px;">You haven't placed any orders yet.</p>
                <a href="index.php" style="color: #1a4d2e; font-weight: bold; text-decoration: underline;">Start Shopping</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Date</th>
                        <th style="width: 45%;">Items Purchased</th>
                        <th style="width: 20%;">Total</th>
                        <th style="width: 15%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="order-row">
                            <!-- DATE COLUMN -->
                            <td>
                                <span class="date-text">
                                    <?php 
                                        if (isset($order['order_date']) && $order['order_date'] instanceof MongoDB\BSON\UTCDateTime) {
                                            echo $order['order_date']->toDateTime()->format('M d, Y');
                                        } else { echo "Recent"; }
                                    ?>
                                </span>
                                <!-- Removed the #ID line here as requested -->
                            </td>

                            <!-- ITEMS COLUMN -->
                            <td class="items-text">
                                <?php 
                                    if (isset($order['items']) && is_array($order['items']) || is_object($order['items'])) {
                                        foreach ($order['items'] as $item) {
                                            // Ensure $item is an array to access keys safely
                                            $item = (array)$item;
                                            $title = isset($item['title']) ? htmlspecialchars($item['title']) : 'Item';
                                            $qty = isset($item['quantity']) ? $item['quantity'] : 1;
                                            
                                            echo "<div>{$qty}x {$title}</div>";
                                        }
                                    } else {
                                        echo "<span style='color:#999;'>No item details available</span>";
                                    }
                                ?>
                            </td>

                            <!-- TOTAL COLUMN -->
                            <td>
                                <span class="total-price">
                                    <?php 
                                        $total = isset($order['total_price']) ? $order['total_price'] : 0;
                                        echo number_format($total, 2); 
                                    ?> TL
                                </span>
                            </td>

                            <!-- STATUS COLUMN -->
                            <td>
                                <span class="status-badge">Paid</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

<?php include 'footer.php'; ?>

</body>
</html>