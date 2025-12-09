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

    <!-- 5. Product Carousel (You Might Be Interested In) -->
    <div style="background: #fff; padding: 40px 0;">
        <?php include 'product_carousel.php'; ?>
    </div>

    <!-- 6. Footer -->
    <?php include 'footer.php'; ?>

</body>
</html>