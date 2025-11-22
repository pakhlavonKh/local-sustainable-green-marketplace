<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart count from PHP session (if you use $_SESSION['cart'])
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['qty'] ?? 1;
    }
}
?>
<nav class="navbar">
    <div class="container nav-inner">
        <a href="index.php" class="navbar-brand">
            <span class="brand-icon">Leaf</span>
            <span class="brand-text"><span>Leaf</span> Green Market</span>
        </a>

        <!-- Mobile burger -->
        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>

            <?php if (is_logged_in()): ?>
                <li><a href="add_product.php">Add Product</a></li>
                <li>
                    <a href="cart.php">
                        Cart (<span id="cart-count"><?php echo $cartCount; ?></span>)
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <?php echo h($_SESSION['user_name']); ?> Down Arrow
                    </a>
                    <div class="dropdown-menu">
                        <a href="logout.php" class="dropdown-item">Log Out</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="login.php">Log In</a></li>
                <li><a href="register.php" class="btn btn-small">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script>
document.getElementById('navToggle').addEventListener('click', function () {
    const links = document.getElementById('navLinks');
    this.classList.toggle('open');
    links.classList.toggle('open');
});
</script>