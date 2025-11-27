<?php
require 'config.php';

// Helper function to safely read JSON files
function read_json_safe($filename, $default = []) {
    return file_exists($filename) ? json_decode(file_get_contents($filename), true) : $default;
}

// Read Data from your JSON files
$products = read_json_safe(PRODUCTS_FILE);
$users    = read_json_safe(USERS_FILE);

// Build user map
$userById = [];
foreach ($users as $u) {
    if (isset($u['id'])) {
        $userById[$u['id']] = $u;
    }
}

// Check if a category is selected (to hide showcase on inner pages)
$selectedCategory = $_GET['category'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaf Leaf Green Market</title>
    <!-- v23 for fresh cache -->
    <link rel="stylesheet" href="style.css?v=23"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- 1. Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- 2. Hero Slider -->
    <?php include 'hero.php'; ?>

    <!-- 3. The Dynamic Catalog (This contains the CATEGORY GRID) -->
    <!-- It must be included here so the category strip appears first -->
    <?php include 'catalog.php'; ?>


    <!-- === START: PRODUCT SHOWCASE CAROUSEL (Only on Homepage) === -->
    <?php if (!$selectedCategory): ?>
        
        <!-- A. The Slim Separator Image -->
        <div class="section-separator-container">
            <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1950&q=80" class="section-separator-image" alt="Fields">
            <div class="separator-overlay-text">Curated for You</div>
        </div>

        <!-- B. Products of the Month Carousel -->
        <?php 
        // Dummy data for showcase
        $showcaseProducts = [
            ['id'=>201, 'title'=>'Artisanal Sourdough', 'price'=>12.00, 'seller'=>'Bakery Lane', 'image'=>'https://images.unsplash.com/photo-1585476263060-b55d7612e69a?auto=format&fit=crop&w=600&q=80'],
            ['id'=>202, 'title'=>'Organic Honey (Raw)', 'price'=>25.00, 'seller'=>'Bee Happy',   'image'=>'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=600&q=80'],
            ['id'=>203, 'title'=>'Heirloom Tomatoes',   'price'=>8.50,  'seller'=>'Green Farm',  'image'=>'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80'],
            ['id'=>204, 'title'=>'Fresh Goat Cheese',   'price'=>18.00, 'seller'=>'Dairy Co.',   'image'=>'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=600&q=80'],
            ['id'=>205, 'title'=>'Handmade Soap',       'price'=>9.00,  'seller'=>'Pure Life',   'image'=>'https://images.unsplash.com/photo-1600857062241-98e5dba7f214?auto=format&fit=crop&w=600&q=80'],
            ['id'=>206, 'title'=>'Olive Oil (Extra)',   'price'=>35.00, 'seller'=>'Olive Grove', 'image'=>'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=600&q=80'],
        ];
        ?>
        <section class="product-showcase-section">
            
            <div class="showcase-header">
                <div>
                    <h2 class="showcase-title">Products of the Month</h2>
                    <p class="showcase-subtitle">Handpicked favorites from our local artisans.</p>
                </div>
                <a href="index.php?category=all" class="view-all-btn">View All Products &rarr;</a>
            </div>

            <div class="carousel-container">
                <button class="scroll-btn scroll-left" onclick="scrollCarousel(-1)"><i class="fas fa-chevron-left"></i></button>
                <button class="scroll-btn scroll-right" onclick="scrollCarousel(1)"><i class="fas fa-chevron-right"></i></button>

                <div class="product-carousel" id="showcaseCarousel">
                    <?php foreach ($showcaseProducts as $sp): ?>
                        <a href="product_details.php?id=<?php echo $sp['id']; ?>" class="carousel-card">
                            <div class="carousel-image-wrapper">
                                <img src="<?php echo $sp['image']; ?>" alt="<?php echo htmlspecialchars($sp['title']); ?>">
                            </div>
                            <div class="carousel-info">
                                <div class="carousel-title"><?php echo htmlspecialchars($sp['title']); ?></div>
                                <div class="carousel-price">₺<?php echo number_format($sp['price'], 2); ?></div>
                                <div class="carousel-seller">By <?php echo htmlspecialchars($sp['seller']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </section>

        <script>
            function scrollCarousel(direction) {
                const carousel = document.getElementById('showcaseCarousel');
                const scrollAmount = 300; 
                carousel.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
            }
        </script>
    <?php endif; ?>
    <!-- === END SHOWCASE === -->

    <!-- 4. Footer -->
    <?php include 'footer.php'; ?>
    
    <script src="cart.js"></script>
</body>
</html>