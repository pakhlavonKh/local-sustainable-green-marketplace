<?php
// 1. Start Session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load Language Settings
require_once 'lang_config.php';

// IMPORTANT: There is NO redirect to login.php here.
// This ensures the homepage is visible to everyone (Guests & Users).
?>

<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaf Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css?v=25"> 
    <link rel="stylesheet" href="carousel_style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- 1. Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- 2. Hero Slider (The Big Images) -->
    <?php include 'hero.php'; ?>

    <!-- 3. Categories Grid (Kiler, Firin, etc.) -->
    <!-- Added white background container for clean look -->
    <div style="background: #fff; padding-bottom: 40px; padding-top: 20px;">
        <?php include 'catalog.php'; ?>
    </div>

    <!-- 4. Transition Banner (The Parallax Image) -->
    <div style="background: #f0f0f0; padding: 0; margin: 0;">
        <?php include 'transition_banner.php'; ?>
    </div>

    <!-- 4.5 About Section Teaser -->
    <div style="background: #fff; padding: 60px 20px;">
        <div class="about-teaser">
            <div class="about-teaser-content">
                <span class="about-label"><?php echo isset($text['about_header']) ? $text['about_header'] : 'About Us'; ?></span>
                <h2><?php echo isset($text['about_teaser_title']) ? $text['about_teaser_title'] : 'Supporting Local Communities & Sustainable Living'; ?></h2>
                <p><?php echo isset($text['about_teaser_text']) ? $text['about_teaser_text'] : 'Leaf Market connects local producers with conscious consumers. We believe in sustainable practices, reducing carbon footprints, and building stronger communities through local commerce.'; ?></p>
                <a href="about.php" class="about-teaser-btn">
                    <?php echo isset($text['learn_more']) ? $text['learn_more'] : 'Learn More About Us'; ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 5. Product Carousel (You Might Be Interested In) -->
    <div style="background: #fff; padding: 40px 0;">
        <?php include 'product_carousel.php'; ?>
    </div>

    <!-- 6. Footer -->
    <?php include 'footer.php'; ?>

</body>
</html>