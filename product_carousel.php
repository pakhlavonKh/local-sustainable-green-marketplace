<?php
require_once 'lang_config.php';
require_once 'data_products.php';

// ... (Your existing shuffling logic stays the same) ...
$carousel_items = array_values($products_db); // Convert to list
shuffle($carousel_items);
$carousel_items = array_slice($carousel_items, 0, 8);
?>

<div class="carousel-container">
    <div class="carousel-header">
        <h2><?php echo isset($text['carousel_title']) ? $text['carousel_title'] : 'You Might Be Interested In'; ?></h2>
        <div class="carousel-arrows"><span>&larr;</span><span>&rarr;</span></div>
    </div>

    <div class="carousel-track">
        <?php foreach ($carousel_items as $item): ?>
            <?php 
                $id = $item['id'];
                // --- NEW TRANSLATION LOGIC ---
                $display_title = ($_SESSION['lang'] == 'tr' && !empty($item['title_tr'])) 
                                 ? $item['title_tr'] 
                                 : $item['title'];

                $price = $item['price'];
                $stock = $item['stock']; 
                $img = $item['image'];
            ?>
            <div class="carousel-card">
                <a href="product_detail.php?id=<?php echo $id; ?>" class="carousel-link">
                    <div class="carousel-image-wrapper">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="product">
                        <?php if($stock < 5): ?>
                            <span class="badge-low-stock"><?php echo isset($text['low_stock']) ? $text['low_stock'] : 'Low Stock'; ?></span>
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