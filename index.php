<?php
// FIX: Use require_once to prevent "Cannot redeclare function" error
require_once 'config.php';

// Helper function to safely read JSON files (Only if not in config.php)
if (!function_exists('read_json_safe')) {
    function read_json_safe($filename, $default = []) {
        return file_exists($filename) ? json_decode(file_get_contents($filename), true) : $default;
    }
}

// Read Data
// Note: If you are moving to SQL, you might eventually remove these JSON lines
if (defined('PRODUCTS_FILE')) {
    $products = read_json_safe(PRODUCTS_FILE);
}
if (defined('USERS_FILE')) {
    $users = read_json_safe(USERS_FILE);
}

// Check if a category is selected
$selectedCategory = $_GET['category'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaf Leaf Green Market</title>
    <link rel="stylesheet" href="style.css?v=25"> 
    <link rel="stylesheet" href="carousel_style.css"> <!-- Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- 1. Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- 2. Hero Slider -->
    <?php include 'hero.php'; ?>

    <!-- 3. Catalog (Category Grid) -->
    <?php include 'catalog.php'; ?>

    <!-- 4. HOMEPAGE ONLY SECTIONS -->
    <?php if (!$selectedCategory): ?>
        
        <!-- NEW: Smooth Transition Banner -->
        <!-- This sits between Categories (Catalog) and the Carousel -->
        <?php include 'transition_banner.php'; ?>

        <!-- The "You Might Be Interested" Carousel -->
        <?php include 'product_carousel.php'; ?>

    <?php endif; ?>

    <!-- 5. Footer -->
    <?php include 'footer.php'; ?>
    
    <script src="cart.js"></script>
</body>
</html>