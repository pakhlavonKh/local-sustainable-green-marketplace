<?php
require_once 'lang_config.php';

// Cart is stored in $_SESSION['cart']
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$total_price = 0;
$total_co2 = 0;
$item_count = 0;

foreach($cart_items as $item) {
    $total_price += ($item['price'] * $item['quantity']);
    $total_co2 += ($item['co2_saved'] * $item['quantity']);
    $item_count += $item['quantity'];
}

$phones_charged = ($total_co2 > 0) ? floor($total_co2 / 0.015) : 0;
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($text['cart_title']) ? $text['cart_title'] : 'Basket'; ?> - Leaf Market</title>
    <link rel="stylesheet" href="style.css?v=26">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background: #fdfbf7; margin: 0; color: #333; }
        .cart-container { max-width: 900px; margin: 40px auto; padding: 20px; min-height: 60vh; }
        .cart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .cart-header h1 { color: #1a4d2e; margin: 0; }
        
        /* Impact Box */
        .impact-box { background: linear-gradient(135deg, #1a4d2e 0%, #2f855a 100%); color: white; padding: 25px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; box-shadow: 0 10px 20px rgba(26, 77, 46, 0.2); }
        .impact-stat h3 { margin: 0; font-size: 32px; font-weight: 800; }
        .impact-stat p { margin: 0; font-size: 14px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;}
        .impact-fun { font-size: 12px; background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 4px; margin-top: 5px; display: inline-block; }
        
        /* Cart List */
        .cart-list { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .cart-item { display: flex; align-items: center; padding: 20px; border-bottom: 1px solid #eee; }
        .cart-item:last-child { border-bottom: none; }
        .item-img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; margin-right: 20px; }
        .item-details { flex-grow: 1; }
        .item-title { font-weight: bold; font-size: 18px; color: #1a4d2e; display: block; }
        .item-eco { font-size: 12px; color: #15803d; background: #dcfce7; padding: 3px 8px; border-radius: 10px; font-weight: bold; margin-top: 5px; display: inline-block;}
        
        /* Quantity & Price */
        .qty-controls { display: flex; align-items: center; gap: 10px; margin-right: 30px; }
        .qty-btn { width: 28px; height: 28px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #333; font-weight: bold; font-size: 16px; transition: all 0.2s; cursor: pointer; }
        .qty-btn:hover { background: #e5e7eb; transform: scale(1.1); }
        .qty-num { font-weight: bold; min-width: 20px; text-align: center; font-size: 16px; }
        .item-price { font-weight: bold; font-size: 18px; min-width: 80px; text-align: right; }
        .remove-btn { color: #ef4444; margin-left: 20px; cursor: pointer; transition: color 0.2s; }
        .remove-btn:hover { color: #b91c1c; }
        
        /* Checkout */
        .checkout-actions { margin-top: 30px; text-align: right; }
        .btn-checkout { background: #e11d48; color: white; padding: 15px 40px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 18px; transition: 0.3s; display: inline-flex; align-items: center; gap: 10px; border: none; cursor: pointer; }
        .btn-checkout:hover { background: #be123c; transform: translateY(-2px); }
        
        .empty-cart { text-align: center; padding: 60px; }
        .empty-icon { font-size: 60px; color: #ccc; margin-bottom: 20px; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="cart-container">
    <div class="cart-header">
        <h1><?php echo isset($text['cart_title']) ? $text['cart_title'] : 'Your Green Basket'; ?></h1>
        <a href="index.php" style="color:#666; text-decoration:none;">&larr; <?php echo isset($text['continue_shop']) ? $text['continue_shop'] : 'Continue Shopping'; ?></a>
    </div>

    <?php if($item_count > 0): ?>
        <div class="impact-box">
            <div>
                <p><?php echo isset($text['total_carbon']) ? $text['total_carbon'] : 'Total Carbon Saved'; ?></p>
                <h3>-<?php echo number_format($total_co2, 1); ?> kg CO₂</h3>
                <span class="impact-fun">
                    <?php echo sprintf(isset($text['impact_fun_fact']) ? $text['impact_fun_fact'] : '%s phones charged', $phones_charged); ?>
                </span>
            </div>
            <div style="text-align: right;">
                <p><?php echo isset($text['total_price']) ? $text['total_price'] : 'Total Price'; ?></p>
                <h3><?php echo number_format($total_price, 2); ?> <?php echo isset($text['currency']) ? $text['currency'] : 'TL'; ?></h3>
            </div>
        </div>

        <div class="cart-list">
            <?php foreach($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image']; ?>" class="item-img" alt="Product">
                    <div class="item-details">
                        <span class="item-title">
                            <?php 
                                $orig = $item['title'];
                                echo (isset($text['products'][$orig])) ? $text['products'][$orig] : $orig; 
                            ?>
                        </span>
                        <span class="item-eco"><i class="fas fa-cloud"></i> -<?php echo $item['co2_saved']; ?>kg CO₂</span>
                    </div>
                    
                    <div class="qty-controls">
                        <a href="cart_action.php?action=decrease&id=<?php echo $item['id']; ?>" class="qty-btn">-</a>
                        <span class="qty-num"><?php echo $item['quantity']; ?></span>
                        <a href="cart_action.php?action=increase&id=<?php echo $item['id']; ?>" class="qty-btn">+</a>
                    </div>

                    <span class="item-price">
                        <?php echo number_format($item['price'] * $item['quantity'], 2); ?> <?php echo isset($text['currency']) ? $text['currency'] : 'TL'; ?>
                    </span>
                    
                    <a href="cart_action.php?action=remove&id=<?php echo $item['id']; ?>" class="remove-btn"><i class="fas fa-trash"></i></a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-actions">
            <a href="cart_action.php?action=clear" style="color: #999; text-decoration: underline; margin-right: 20px; font-size: 14px;">
                <?php echo isset($text['clear_cart']) ? $text['clear_cart'] : 'Clear Cart'; ?>
            </a>

            <!-- SMART BUTTON LOGIC -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- If Logged In: Go to Real Checkout -->
                <a href="checkout.php" class="btn-checkout">
                    <?php echo isset($text['checkout']) ? $text['checkout'] : 'Checkout'; ?> <i class="fas fa-arrow-right"></i>
                </a>
            <?php else: ?>
                <!-- If Guest: Go to Login -->
                <a href="login.php" class="btn-checkout">
                    <i class="fas fa-lock"></i> Login to Checkout
                </a>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-basket empty-icon"></i>
            <h2><?php echo isset($text['cart_empty']) ? $text['cart_empty'] : 'Empty'; ?></h2>
            <p><?php echo isset($text['cart_empty_sub']) ? $text['cart_empty_sub'] : 'Go shop!'; ?></p>
            <a href="index.php" class="btn-checkout" style="background: #1a4d2e; margin-top: 20px;">
                <?php echo isset($text['start_shop']) ? $text['start_shop'] : 'Shop Now'; ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!-- Scroll Position Script -->
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        var scrollpos = sessionStorage.getItem('cart_scrollpos');
        if (scrollpos) {
            window.scrollTo(0, scrollpos);
            sessionStorage.removeItem('cart_scrollpos'); 
        }
    });
    window.addEventListener("beforeunload", function(e) {
        sessionStorage.setItem('cart_scrollpos', window.scrollY);
    });
</script>

</body>
</html>
