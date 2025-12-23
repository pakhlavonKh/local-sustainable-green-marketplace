<?php 
require_once 'lang_config.php'; 
?>

<footer class="site-footer">
    <div class="footer-container">
        
        <div class="footer-col">
            <h3><?php echo $text['contact_header']; ?></h3>
            <div class="contact-block">
                <p style="margin-bottom: 10px; color: #666; font-size: 13px;">
                    <?php echo $text['contact_desc']; ?>
                </p>
                <a href="mailto:support@leafmarket.com" class="contact-link">
                    <i class="far fa-envelope"></i>
                    <span>support@leafmarket.com</span>
                </a>
            </div>
        </div>

        <div class="footer-col">
            <h3><?php echo $text['about_header']; ?></h3>
            <ul>
                <li><a href="about.php"><?php echo $text['about_link']; ?></a></li>
                <li><a href="category_page.php?category=all">Shop All Products</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3><?php echo $text['you_like_header']; ?></h3>
            <ul>
                <li><a href="category_page.php?category=fresh_produce"><?php echo $text['cat_fresh']; ?></a></li>
                <li><a href="category_page.php?category=dairy_eggs"><?php echo $text['cat_dairy']; ?></a></li>
                <li><a href="category_page.php?category=bakery"><?php echo $text['cat_bakery']; ?></a></li>
                <li><a href="category_page.php?category=pantry"><?php echo $text['cat_pantry']; ?></a></li>
                <li><a href="category_page.php?category=beverages"><?php echo $text['cat_bev']; ?></a></li>
                <li><a href="category_page.php?category=home_garden"><?php echo $text['cat_home']; ?></a></li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Shopping Cart</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php">My Account</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>

    </div>

    <div class="footer-bottom-text">
        &copy; <?php echo date("Y"); ?> Leaf Leaf Green Market. <?php echo $text['rights']; ?>
    </div>
</footer>
