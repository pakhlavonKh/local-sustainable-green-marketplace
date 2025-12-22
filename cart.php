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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($text['cart_title']) ? $text['cart_title'] : 'Basket'; ?> - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css?v=26">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="cart-page-body">
        

<div class="cart-container">
    <div class="cart-header">
        <h1><?php echo isset($text['cart_title']) ? $text['cart_title'] : 'Your Green Basket'; ?></h1>
        <a href="index.php" style="color:#666; text-decoration:none;">&larr; <?php echo isset($text['continue_shop']) ? $text['continue_shop'] : 'Continue Shopping'; ?></a>
    </div>

    <?php if (isset($_SESSION['cart_notice'])): ?>
        <div class="cart-notice <?php echo $_SESSION['cart_notice']['type']; ?>">
            <?php 
            echo htmlspecialchars($_SESSION['cart_notice']['message']); 
            unset($_SESSION['cart_notice']);
            ?>
        </div>
    <?php endif; ?>

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
