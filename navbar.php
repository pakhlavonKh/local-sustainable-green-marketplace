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

// Get wishlist count
$wishlist_items = isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist']) ? $_SESSION['wishlist'] : [];
$wishlist_count = count($wishlist_items);

// Determine Profile Link based on Role
$profile_link = 'profile.php';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $profile_link = 'seller_dashboard.php';
}
?>

<nav class="navbar">
    <div class="nav-top">
        <div class="logo"><a href="index.php">Leaf Leaf Market</a></div>
        
        <div class="nav-links">
            <a href="index.php" class="nav-link"><i class="fas fa-home"></i> <?php echo isset($text['nav_home']) ? $text['nav_home'] : 'Home'; ?></a>
            <a href="category_page.php?category=all" class="nav-link"><i class="fas fa-th-large"></i> Catalog</a>
            <a href="about.php" class="nav-link"><i class="fas fa-info-circle"></i> <?php echo isset($text['nav_about']) ? $text['nav_about'] : 'About'; ?></a>
        </div>

        <div class="user-actions">
            <a href="wishlist.php" class="wishlist-icon-btn">
                <i class="far fa-heart"></i>
                <?php if ($wishlist_count > 0): ?>
                    <span class="wishlist-count"><?php echo $wishlist_count; ?></span>
                <?php endif; ?>
            </a>
            
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
            
            <!-- Language Switcher -->
            <div class="header-language">
                <a href="?lang=en" class="lang-link <?php echo ($_SESSION['lang'] == 'en') ? 'active' : ''; ?>" title="English">EN</a>
                <span class="lang-divider">|</span>
                <a href="?lang=tr" class="lang-link <?php echo ($_SESSION['lang'] == 'tr') ? 'active' : ''; ?>" title="Türkçe">TR</a>
            </div>
        </div>
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

<!-- Product Detail Modal -->
<div id="productModal" class="product-modal" style="display: none;">
    <div class="product-modal-content">
        <button class="product-modal-close" onclick="closeProductModal()" aria-label="Close">&times;</button>
        <div id="modalProductBody"></div>
    </div>
</div>

<script src="product_modal.js"></script>
