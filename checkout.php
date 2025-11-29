<?php
session_start();
require_once 'db_connect.php';
require_once 'lang_config.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header("Location: cart.php"); 
    exit();
}

$total_price = 0;
foreach($cart as $item) {
    $total_price += ($item['price'] * $item['quantity']);
}

// 2. FETCH USER DATA (To Pre-fill Form)
$db = getDBConnection();
$saved_address = '';
$saved_city = '';
$saved_phone = '';

if ($db) {
    $usersCollection = $db->users;
    try {
        // Find the user by ID
        $user_data = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
        
        // If user found, extract saved details
        if ($user_data) {
            if (isset($user_data['address'])) $saved_address = $user_data['address'];
            if (isset($user_data['city']))    $saved_city    = $user_data['city'];
            if (isset($user_data['phone']))   $saved_phone   = $user_data['phone'];
        }
    } catch (Exception $e) {
        // Handle ID errors silently
    }
}

// 3. PROCESS ORDER
$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $db) {
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $phone = trim($_POST['phone']);
    $delivery_type = $_POST['delivery_type'];
    // Fix: Ensure payment method is set
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'credit_card';
    
    if(empty($address) || empty($city) || empty($phone)) {
        $error = "Please fill in all shipping details.";
    } else {
        $productsCollection = $db->products;
        $ordersCollection = $db->orders;
        $usersCollection = $db->users;

        // Calculate Impact
        $delivery_impact = 0;
        if($delivery_type == 'bike' || $delivery_type == 'walk') $delivery_impact = 0;
        elseif($delivery_type == 'public') $delivery_impact = 0.5;
        else $delivery_impact = 2.0;

        // Create Order
        $order = [
            'user_id' => $_SESSION['user_id'],
            'user_name' => $_SESSION['username'],
            'shipping' => [
                'address' => $address,
                'city' => $city,
                'phone' => $phone,
                'method' => $delivery_type,
                'co2_cost' => $delivery_impact
            ],
            'payment' => [
                'method' => $payment_method,
                'status' => 'paid'
            ],
            'items' => array_values($cart),
            'total_price' => $total_price,
            'order_date' => new MongoDB\BSON\UTCDateTime()
        ];

        // Update Stock
        foreach ($cart as $item) {
            $productsCollection->updateOne(
                ['id' => (int)$item['id']], 
                ['$inc' => ['stock' => -((int)$item['quantity'])]]
            );
        }

        // Save Order
        $ordersCollection->insertOne($order);

        // SAVE USER DATA FOR NEXT TIME
        $usersCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
            ['$set' => [
                'address' => $address,
                'city' => $city,
                'phone' => $phone
            ]]
        );
        
        unset($_SESSION['cart']);
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background: #fdfbf7; margin: 0; color: #333; }
        .checkout-container { max-width: 900px; margin: 40px auto; padding: 20px; display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; }
        .box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        h2 { color: #1a4d2e; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; box-sizing: border-box; }
        .option-card { border: 2px solid #eee; border-radius: 10px; padding: 15px; margin-bottom: 15px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: space-between; }
        .option-card:hover { border-color: #1a4d2e; background: #f0fdf4; }
        .option-card input { margin-right: 15px; transform: scale(1.5); accent-color: #1a4d2e; }
        .info-block strong { display: block; color: #333; font-size: 16px; }
        .info-block small { color: #666; }
        .eco-badge { background: #dcfce7; color: #166534; font-size: 11px; padding: 4px 8px; border-radius: 10px; font-weight: bold; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; }
        .total-row { font-weight: 800; font-size: 20px; border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px; color: #1a4d2e; }
        .btn-confirm { width: 100%; background: #1a4d2e; color: white; padding: 15px; border: none; border-radius: 30px; font-weight: 800; font-size: 18px; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .btn-confirm:hover { background: #143d23; transform: translateY(-2px); }
        .success-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background:white; z-index:999; display: flex; justify-content: center; align-items: center; text-align: center; }
        .icon-circle { width: 80px; height: 80px; background: #dcfce7; color: #166534; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px auto; }
        @media (max-width: 768px) { .checkout-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<?php if($success): ?>
    <div class="success-overlay">
        <div>
            <div class="icon-circle"><i class="fas fa-check"></i></div>
            <h1 style="color:#1a4d2e;">Order Confirmed!</h1>
            <p style="color:#666; margin-bottom:30px;">Thank you for shopping sustainably.<br>Your order #<?php echo rand(10000,99999); ?> has been placed.</p>
            <a href="index.php" class="btn-confirm" style="text-decoration:none; padding:15px 40px;">Back to Market</a>
        </div>
    </div>
<?php else: ?>

<div class="checkout-container">
    
    <!-- LEFT: Shipping Form -->
    <div class="box">
        <h2>Shipping Details</h2>
        <?php if($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
        
        <form action="checkout.php" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <!-- This comes from session, so it is read-only -->
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="background:#f9f9f9;">
            </div>
            
            <div class="form-group">
                <label>Delivery Address</label>
                <!-- KEY FIX: Value attribute is now populated with PHP variable -->
                <input type="text" name="address" class="form-control" placeholder="Street name, Apt No..." value="<?php echo htmlspecialchars($saved_address); ?>" required>
            </div>
            
            <div class="form-group">
                <label>City / District</label>
                <input type="text" name="city" class="form-control" placeholder="e.g. Kadikoy, Istanbul" value="<?php echo htmlspecialchars($saved_city); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" class="form-control" placeholder="05XX XXX XX XX" value="<?php echo htmlspecialchars($saved_phone); ?>" required>
            </div>

            <h2 style="margin-top:30px;">Green Delivery Options</h2>
            
            <label class="option-card">
                <div style="display:flex; align-items:center;">
                    <input type="radio" name="delivery_type" value="bike" checked>
                    <div class="info-block">
                        <strong>Bike Courier</strong>
                        <small>Within 5km • 1-2 Hours</small>
                    </div>
                </div>
                <span class="eco-badge">Zero CO₂</span>
            </label>

            <label class="option-card">
                <div style="display:flex; align-items:center;">
                    <input type="radio" name="delivery_type" value="walk">
                    <div class="info-block">
                        <strong>Walking Courier</strong>
                        <small>Within 1km • 30 Mins</small>
                    </div>
                </div>
                <span class="eco-badge">Zero CO₂</span>
            </label>

            <label class="option-card">
                <div style="display:flex; align-items:center;">
                    <input type="radio" name="delivery_type" value="public">
                    <div class="info-block">
                        <strong>Public Transport</strong>
                        <small>Bus/Metro • Same Day</small>
                    </div>
                </div>
                <span class="eco-badge" style="background:#fef9c3; color:#854d0e;">Low CO₂</span>
            </label>

            <label class="option-card">
                <div style="display:flex; align-items:center;">
                    <input type="radio" name="delivery_type" value="cargo">
                    <div class="info-block">
                        <strong>Standard Cargo</strong>
                        <small>Van/Truck • 1-3 Days</small>
                    </div>
                </div>
                <span class="eco-badge" style="background:#fee2e2; color:#991b1b;">High CO₂</span>
            </label>

            <!-- Payment Method -->
            <h2 style="margin-top:30px;">Payment Method</h2>

            <label class="option-card">
                <div style="display:flex; align-items:center;">
                    <input type="radio" name="payment_method" value="credit_card" checked>
                    <div class="info-block">
                        <strong>Credit / Debit Card</strong>
                        <small>Secure encrypted payment</small>
                    </div>
                </div>
                <i class="far fa-credit-card" style="font-size:24px; color:#555;"></i>
            </label>

            <label class="option-card">
                <div style="display:flex; align-items:center;">
                    <input type="radio" name="payment_method" value="cash_on_delivery">
                    <div class="info-block">
                        <strong>Cash on Delivery</strong>
                        <small>Pay at your door</small>
                    </div>
                </div>
                <i class="fas fa-money-bill-wave" style="font-size:24px; color:#555;"></i>
            </label>
            
            <button type="submit" id="real-submit" style="display:none;"></button>
        </form>
    </div>

    <!-- RIGHT: Order Summary -->
    <div class="box" style="height: fit-content;">
        <h2>Order Summary</h2>
        
        <?php foreach($cart as $item): ?>
            <div class="summary-row">
                <span><?php echo $item['quantity']; ?>x <?php echo htmlspecialchars($item['title']); ?></span>
                <span><?php echo number_format($item['price'] * $item['quantity'], 2); ?> TL</span>
            </div>
        <?php endforeach; ?>
        
        <div class="summary-row" style="margin-top:20px; border-top:1px solid #eee; padding-top:10px;">
            <span>Subtotal</span>
            <span><?php echo number_format($total_price, 2); ?> TL</span>
        </div>
        <div class="summary-row">
            <span>Delivery Fee</span>
            <span>0.00 TL</span>
        </div>
        
        <div class="summary-row total-row">
            <span>TOTAL</span>
            <span><?php echo number_format($total_price, 2); ?> TL</span>
        </div>

        <button onclick="document.getElementById('real-submit').click()" class="btn-confirm">
            Confirm Order <i class="fas fa-arrow-right"></i>
        </button>
        
        <p style="font-size:12px; color:#999; text-align:center; margin-top:15px;">
            <i class="fas fa-lock"></i> Secure Payment by LeafPay
        </p>
    </div>

</div>
<?php endif; ?>

<?php include 'footer.php'; ?>

</body>
</html>