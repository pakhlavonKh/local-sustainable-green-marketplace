<nav class="navbar">
    <div class="container">
        <a href="index.php" class="navbar-brand"><span>Leaf</span> Yeşil Pazar</a>
        <ul class="nav-links">
            <li><a href="index.php">Ana Sayfa</a></li>
            <?php if (is_logged_in()): ?>
                <li><a href="add_product.php">Ürün Ekle</a></li>
                <li><a href="cart.php">Sepet (<span id="cart-count">0</span>)</a></li>
                <li class="dropdown">
                    <a href="#"><?php echo h($_SESSION['user_name']); ?> ▼</a>
                    <div class="dropdown-menu">
                        <a href="logout.php" class="dropdown-item">Çıkış Yap</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="login.php">Giriş</a></li>
                <li><a href="register.php">Kayıt Ol</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>