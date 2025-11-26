<?php
require 'config.php';

// Helper function to safely read JSON files
// If the file doesn't exist, it returns an empty array [] instead of crashing
function read_json_safe($filename, $default = []) {
    return file_exists($filename) ? json_decode(file_get_contents($filename), true) : $default;
}

// Read Data from your JSON files
$products = read_json_safe(PRODUCTS_FILE);
$users    = read_json_safe(USERS_FILE);

// Build user map (matches user IDs to names for the "Seller" tag)
$userById = [];
foreach ($users as $u) {
    if (isset($u['id'])) {
        $userById[$u['id']] = $u;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaf Leaf Green Market</title>
    
    <!-- CSS Link with "Cache Buster" (?v=3) to force browser to load new changes -->
    <link rel="stylesheet" href="style.css?v=3">
    
    <!-- FontAwesome for the Heart and Cart icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- 1. The Navbar (Your friend's component) -->
    <?php include 'navbar.php'; ?>

    <!-- 2. The Hero Slider (Your new fullscreen slider) -->
    <?php include 'hero.php'; ?>

    <!-- 3. The Dynamic Catalog (Your Categories & Products logic) -->
    <!-- We pass the $products and $userById variables to this file automatically -->
    <?php include 'catalog.php'; ?>

    <!-- 4. The Footer (Your friend's component) -->
    <?php include 'footer.php'; ?>
    
    <!-- Cart Logic Script -->
    <script src="cart.js"></script>
</body>
</html>