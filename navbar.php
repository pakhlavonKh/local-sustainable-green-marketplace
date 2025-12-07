<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'lang_config.php'; 

$cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$unique_cart_count = count($cart_items);
$cart_notice = $_SESSION['cart_notice'] ?? null;
if ($cart_notice) {
    unset($_SESSION['cart_notice']);
}

// Determine Profile Link based on Role
$profile_link = 'profile.php';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $profile_link = 'seller_dashboard.php';
}
?>

<nav class="navbar">
    <div class="nav-top">
        <div class="logo"><a href="index.php">Leaf Leaf Market</a></div>
        
        <form action="search.php" method="GET" class="search-bar">
            <input type="text" name="q" placeholder="<?php echo isset($text['search_place']) ? $text['search_place'] : 'Search...'; ?>" required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>

        <div class="user-actions">
            <a href="#"><i class="far fa-heart"></i></a>
            
            <?php if(isset($_SESSION['user_id']) && !empty($_SESSION['username'])): ?>
                <span class="user-welcome" style="margin-left: 15px; font-size: 14px;">
                    <i class="far fa-user" style="color:white;"></i> 
                    <a href="<?php echo $profile_link; ?>" style="color: white; text-decoration: none; font-weight: bold;">
                        Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                </span>
                <a href="logout.php" style="font-size: 12px; margin-left: 5px; opacity: 0.8; color: white; text-decoration: underline;">(Logout)</a>
            <?php else: ?>
                <a href="login.php"><i class="far fa-user"></i> <?php echo isset($text['nav_login']) ? $text['nav_login'] : 'Log In'; ?></a>
            <?php endif; ?>

            <a href="cart.php" class="basket-btn">
                <span class="icon-shell">
                    <i class="fas fa-shopping-basket"></i>
                    <span
                        class="basket-count"
                        id="basket-count"
                        data-count="<?php echo $unique_cart_count; ?>"
                    >
                        <?php echo $unique_cart_count; ?>
                    </span>
                </span>
                <span class="basket-label"><?php echo isset($text['basket']) ? $text['basket'] : 'Basket'; ?></span>
            </a>
        </div>
    </div>

    <div class="nav-bottom">
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> <?php echo isset($text['nav_home']) ? $text['nav_home'] : 'Home'; ?></a></li>
            <li><a href="category_page.php?category=fresh_produce"><?php echo isset($text['cat_fresh']) ? $text['cat_fresh'] : 'Fresh Produce'; ?></a></li>
            <li><a href="category_page.php?category=dairy_eggs"><?php echo isset($text['cat_dairy']) ? $text['cat_dairy'] : 'Dairy & Eggs'; ?></a></li>
            <li><a href="category_page.php?category=bakery"><?php echo isset($text['cat_bakery']) ? $text['cat_bakery'] : 'Bakery'; ?></a></li>
            <li><a href="category_page.php?category=pantry"><?php echo isset($text['cat_pantry']) ? $text['cat_pantry'] : 'Pantry'; ?></a></li>
            <li><a href="category_page.php?category=beverages"><?php echo isset($text['cat_bev']) ? $text['cat_bev'] : 'Beverages'; ?></a></li>
            <li><a href="category_page.php?category=home_garden"><?php echo isset($text['cat_home']) ? $text['cat_home'] : 'Home & Garden'; ?></a></li>
            <li class="nav-special"><a href="about.php"><?php echo isset($text['nav_about']) ? $text['nav_about'] : 'About'; ?></a></li>
        </ul>
    </div>
</nav>

<?php if ($cart_notice): ?>
    <div class="toast-stack" aria-live="polite" aria-atomic="true">
        <div class="cart-toast <?php echo ($cart_notice['type'] ?? '') === 'removed' ? 'toast-danger' : 'toast-success'; ?>" id="cart-toast">
            <div class="toast-icon" aria-hidden="true">
                <?php if (($cart_notice['type'] ?? '') === 'removed'): ?>
                    <i class="fas fa-minus-circle"></i>
                <?php else: ?>
                    <i class="fas fa-check-circle"></i>
                <?php endif; ?>
            </div>
            <div class="toast-copy">
                <div class="toast-title">
                    <?php echo ($cart_notice['type'] ?? '') === 'removed' ? 'Item removed' : 'Item added'; ?>
                </div>
                <div class="toast-message">
                    <?php echo htmlspecialchars($cart_notice['message'] ?? 'Basket updated'); ?>
                </div>
            </div>
            <button class="toast-close" type="button" aria-label="Close notification">&times;</button>
        </div>
    </div>
    <script>
        (() => {
            const toast = document.getElementById('cart-toast');
            if (!toast) return;

            const closeToast = () => toast.classList.remove('show');
            setTimeout(() => toast.classList.add('show'), 40);
            setTimeout(closeToast, 4200);
            toast.querySelector('.toast-close')?.addEventListener('click', closeToast);
        })();
    </script>
<?php endif; ?>
