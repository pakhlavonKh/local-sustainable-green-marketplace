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

    <!-- Language Selector -->
    <div class="footer-language">
        <span class="lang-label"><?php echo $text['lang_label']; ?></span>
        <!-- Notice: Added 'active' class logic here for visual feedback -->
        <a href="?lang=en" class="lang-link <?php echo ($_SESSION['lang'] == 'en') ? 'active' : ''; ?>">English</a>
        <span class="divider">|</span>
        <a href="?lang=tr" class="lang-link <?php echo ($_SESSION['lang'] == 'tr') ? 'active' : ''; ?>">Türkçe</a>
    </div>

    <div class="footer-bottom-text">
        &copy; <?php echo date("Y"); ?> Leaf Leaf Green Market. <?php echo $text['rights']; ?>
    </div>
</footer>

<style>
/* --- FOOTER STYLES --- */
.site-footer {
    background-color: #fff;
    padding: 60px 20px 20px 20px;
    font-family: 'Arial', sans-serif;
    color: #000;
    margin-top: 50px;
    border-top: 1px solid #eee;
}

.footer-container {
    max-width: 1300px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
}

.footer-col h3 {
    font-size: 15px;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 25px;
    letter-spacing: 0.5px;
}

.footer-col ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-col ul li {
    margin-bottom: 12px;
}

.footer-col ul li a {
    text-decoration: none;
    color: #000;
    font-size: 13px;
    transition: color 0.2s;
}

.footer-col ul li a:hover {
    color: #666;
    text-decoration: underline;
}

.contact-link {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #000;
    font-weight: 700;
    font-size: 14px;
}

.footer-col i {
    font-size: 18px;
}

/* Social Media Section */
.footer-social {
    max-width: 1300px;
    margin: 50px auto 10px auto;
    display: flex;
    gap: 25px;
    padding-top: 30px;
    justify-content: center;
}

.footer-social a {
    color: #000;
    font-size: 22px;
    transition: transform 0.2s;
    background: #000;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.footer-social a:hover {
    transform: translateY(-3px);
    background: #333;
}

/* Language Selector Styles */
.footer-language {
    text-align: center;
    margin-top: 20px;
    font-size: 13px;
}

.lang-label {
    color: #666;
    margin-right: 8px;
}

.lang-link {
    text-decoration: none;
    color: #999;
    font-weight: bold;
    transition: color 0.2s;
    cursor: pointer;
}

.lang-link:hover {
    color: #000;
}

.lang-link.active {
    color: #000;
    text-decoration: underline;
}

.divider {
    color: #eee;
    margin: 0 8px;
}

.footer-bottom-text {
    text-align: center;
    font-size: 11px;
    color: #888;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .footer-container {
        grid-template-columns: 1fr;
    }
}
</style>