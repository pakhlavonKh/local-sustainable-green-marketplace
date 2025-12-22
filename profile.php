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
$success_msg = '';
$error_msg = '';

// Handle address update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if ($db) {
        try {
            $usersCollection = $db->users;
            $result = $usersCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
                ['$set' => [
                    'address' => $address,
                    'city' => $city,
                    'phone' => $phone
                ]]
            );
            if ($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0) {
                $success_msg = 'Address updated successfully!';
            }
        } catch (Exception $e) {
            error_log('Address update error: ' . $e->getMessage());
            $error_msg = 'Failed to update address.';
        }
    }
}

if ($db) {
    try {
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
    } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
        error_log('MongoDB timeout in profile.php: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('MongoDB error in profile.php: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="profile-page-body">
        

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
        
        <a href="wishlist.php" style="display: block; width: 100%; padding: 15px; background: #1a4d2e; color: white; text-align: center; text-decoration: none; font-weight: bold; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; margin-bottom: 10px; border-radius: 4px;">
            <i class="fas fa-heart"></i> My Favorites
        </a>

        <a href="logout.php" class="btn-logout">Sign Out</a>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
        
        <?php if ($success_msg): ?>
            <div style="background: #f0fdf4; color: #166534; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #bbf7d0;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #fecaca;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_msg); ?>
            </div>
        <?php endif; ?>
        
        <!-- Address Section -->
        <h2 class="section-title">Shipping Address</h2>
        <div style="background: #fff; padding: 30px; border-radius: 10px; margin-bottom: 40px; border: 1px solid #eee;">
            <form method="POST" action="profile.php">
                <input type="hidden" name="update_address" value="1">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Full Address</label>
                        <input type="text" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" class="form-input" placeholder="Street, Building, Apt" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 15px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">City</label>
                        <input type="text" name="city" value="<?php echo isset($user['city']) ? htmlspecialchars($user['city']) : ''; ?>" class="form-input" placeholder="Istanbul" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 15px;">
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" class="form-input" placeholder="+90 555 123 4567" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 15px;">
                </div>
                <button type="submit" style="background: #1a4d2e; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-save"></i> Save Address
                </button>
            </form>
        </div>
        
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