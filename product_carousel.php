<?php
// 1. Load Language Config
require_once 'lang_config.php';

// 2. Try to get products from the main page ($products variable)
$carousel_items = [];

if (isset($products) && is_array($products)) {
    $carousel_items = $products;
} 
elseif (defined('PRODUCTS_FILE')) {
    // Try reading the file if variable is missing
    $content = @file_get_contents(PRODUCTS_FILE);
    $carousel_items = $content ? json_decode($content, true) : [];
}

// 3. SAFETY FALLBACK: If no products found, use this dummy data
// This ensures the carousel is NEVER empty
if (empty($carousel_items)) {
    $carousel_items = [
        ['id'=>1, 'title'=>'Red Apples', 'price'=>25.00, 'stock'=>10, 'image'=>'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=500'],
        ['id'=>2, 'title'=>'Organic Carrots', 'price'=>15.00, 'stock'=>2, 'image'=>'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=500'],
        ['id'=>3, 'title'=>'Free Range Eggs', 'price'=>45.00, 'stock'=>20, 'image'=>'https://images.unsplash.com/photo-1511690656952-34342d5c2899?w=500'],
        ['id'=>4, 'title'=>'Fresh Spinach', 'price'=>20.00, 'stock'=>8, 'image'=>'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=500'],
        ['id'=>5, 'title'=>'Sourdough Bread', 'price'=>35.00, 'stock'=>5, 'image'=>'https://images.unsplash.com/photo-1585476263060-b55d7612e69a?w=500'],
        ['id'=>6, 'title'=>'Honey Jar', 'price'=>85.00, 'stock'=>12, 'image'=>'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=500']
    ];
}

// 4. Randomize
shuffle($carousel_items);
$carousel_items = array_slice($carousel_items, 0, 8);
?>

<div class="carousel-container">
    <div class="carousel-header">
        <!-- Translated Header -->
        <h2><?php echo isset($text['carousel_title']) ? $text['carousel_title'] : 'You Might Be Interested In'; ?></h2>
        
        <div class="carousel-arrows">
            <span>&larr;</span>
            <span>&rarr;</span>
        </div>
    </div>

    <div class="carousel-track">
        <?php foreach ($carousel_items as $item): ?>
            <?php 
                $id = $item['id'] ?? 0;
                
                // --- TRANSLATION LOGIC ---
                // 1. Get English Title
                $original_title = $item['title'] ?? 'Product';
                
                // 2. Check if a translation exists in lang_config.php
                // If yes, use it. If not, stick to English.
                $display_title = (isset($text['products']) && isset($text['products'][$original_title])) 
                                 ? $text['products'][$original_title] 
                                 : $original_title;

                $price = $item['price'] ?? 0;
                $stock = $item['stock'] ?? 10; 
                $img = $item['image'] ?? '';

                // Image Fallback
                if (empty($img) || strpos($img, 'http') === false) {
                    $img = 'https://via.placeholder.com/300x300?text=Leaf+Market'; 
                }
            ?>
            
            <div class="carousel-card">
                <a href="product_detail.php?id=<?php echo $id; ?>" class="carousel-link">
                    <div class="carousel-image-wrapper">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="product">
                        
                        <?php if($stock < 5): ?>
                            <!-- Translated 'Low Stock' Badge -->
                            <span class="badge-low-stock">
                                <?php echo isset($text['low_stock']) ? $text['low_stock'] : 'Low Stock'; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="carousel-info">
                        <h3><?php echo htmlspecialchars($display_title); ?></h3>
                        <div class="price-row">
                            <span class="price">
                                <?php echo number_format($price, 2); ?> 
                                <?php echo isset($text['currency']) ? $text['currency'] : 'TL'; ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>