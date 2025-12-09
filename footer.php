<?php 
// IMPORTANT: Include the language logic
require_once 'lang_config.php'; 
?>

<!-- FOOTER SECTION -->
<footer class="site-footer">
    <div class="footer-container">
        
        <!-- Column 1: Contact Us -->
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

        <!-- Column 2: Help -->
        <div class="footer-col">
            <h3><?php echo $text['help_header']; ?></h3>
            <ul>
                <li><a href="#"><?php echo $text['buy_online']; ?></a></li>
                <li><a href="#"><?php echo $text['payment']; ?></a></li>
                <li><a href="#"><?php echo $text['shipping']; ?></a></li>
            </ul>
        </div>

        <!-- Column 3: Company Info -->
        <div class="footer-col">
            <h3><?php echo $text['about_header']; ?></h3>
            <ul>
                <li><a href="about.php"><?php echo $text['about_link']; ?></a></li> 
                <li><a href="#"><?php echo $text['sustainability']; ?></a></li>
            </ul>
        </div>

        <!-- Column 4: Categories -->
        <div class="footer-col">
            <h3><?php echo $text['you_like_header']; ?></h3>
            <ul>
                <li><a href="index.php?category=fresh_produce"><?php echo $text['cat_fresh']; ?></a></li>
                <li><a href="index.php?category=dairy_eggs"><?php echo $text['cat_dairy']; ?></a></li>
                <li><a href="index.php?category=bakery"><?php echo $text['cat_bakery']; ?></a></li>
                <li><a href="index.php?category=pantry"><?php echo $text['cat_pantry']; ?></a></li>
                <li><a href="index.php?category=beverages"><?php echo $text['cat_bev']; ?></a></li>
                <li><a href="index.php?category=home_garden"><?php echo $text['cat_home']; ?></a></li>
            </ul>
        </div>

    </div>

    <!-- Social Media Icons -->
    <div class="footer-social">
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
    </div>

    <div class="footer-bottom-text">
        &copy; <?php echo date("Y"); ?> Leaf Leaf Green Market. <?php echo $text['rights']; ?>
    </div>
</footer>
