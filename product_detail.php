<?php
require_once 'lang_config.php'; // Load translations

// 1. GET THE PRODUCT ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. THE ADVANCED "MINI DATABASE"
$products_db = [
    1 => [
        'title'=>'Red Apples', 'price'=>25.00, 'stock'=>10, 
        'desc'=>'Fresh, crisp, and locally grown red apples. Perfect for snacking or baking.', 
        'image'=>'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=800',
        'delivery_mode' => 'bike', 'packaging_type' => 'plastic_free', 'location' => 'Kadikoy, Istanbul',
        'seller_name' => 'Ahmet\'s Orchard', 'seller_id' => 101, 'distance' => '3.2 km', 'co2_saved' => '0.5',
        'reviews' => [
            ['user' => 'Elif K.', 'stars' => 5, 'comment' => 'The freshest apples I have tasted in years!'],
            ['user' => 'Can B.', 'stars' => 4, 'comment' => 'Very juicy, but delivery took an hour.']
        ]
    ],
    2 => [
        'title'=>'Organic Carrots', 'price'=>15.00, 'stock'=>2,  
        'desc'=>'Crunchy organic carrots harvested this morning. High in Vitamin A.',    
        'image'=>'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=800',
        'delivery_mode' => 'bike', 'packaging_type' => 'recycled', 'location' => 'Besiktas, Istanbul',
        'seller_name' => 'Green Roots Farm', 'seller_id' => 102, 'distance' => '5.1 km', 'co2_saved' => '0.3',
        'reviews' => [['user' => 'Selin Y.', 'stars' => 5, 'comment' => 'Perfect for my morning juice. Real organic taste.']]
    ],
    3 => [
        'title'=>'Free Range Eggs', 'price'=>45.00, 'stock'=>20, 
        'desc'=>'Farm-fresh eggs from happy, free-roaming chickens.',                   
        'image'=>'https://images.unsplash.com/photo-1511690656952-34342d5c2899?w=800',
        'delivery_mode' => 'walk', 'packaging_type' => 'recycled', 'location' => 'Moda, Istanbul',
        'seller_name' => 'Happy Hen Coop', 'seller_id' => 103, 'distance' => '1.2 km', 'co2_saved' => '0.8',
        'reviews' => [['user' => 'Murat D.', 'stars' => 5, 'comment' => 'Zero broken eggs.'], ['user' => 'Ayse T.', 'stars' => 5, 'comment' => 'Huge difference from supermarket eggs!']]
    ],
    4 => [
        'title'=>'Fresh Spinach', 'price'=>20.00, 'stock'=>8,  
        'desc'=>'Leafy green spinach, washed and ready to eat.',                         
        'image'=>'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=800',
        'delivery_mode' => 'public', 'packaging_type' => 'plastic_free', 'location' => 'Uskudar, Istanbul',
        'seller_name' => 'Uskudar Gardens', 'seller_id' => 104, 'distance' => '4.5 km', 'co2_saved' => '0.4',
        'reviews' => [['user' => 'Deniz A.', 'stars' => 4, 'comment' => 'Very fresh but needs washing.']]
    ],
    5 => [
        'title'=>'Sourdough Bread', 'price'=>35.00, 'stock'=>5,  
        'desc'=>'Artisanal sourdough bread with a perfect crust.',                       
        'image'=>'https://images.unsplash.com/photo-1585476263060-b55d7612e69a?w=800',
        'delivery_mode' => 'cargo', 'packaging_type' => 'standard', 'location' => 'Beyoglu, Istanbul',
        'seller_name' => 'Pera Bakery', 'seller_id' => 105, 'distance' => '8.0 km', 'co2_saved' => '0.1',
        'reviews' => [['user' => 'John S.', 'stars' => 5, 'comment' => 'Best bread in Istanbul.']]
    ],
    6 => [
        'title'=>'Honey Jar', 'price'=>85.00, 'stock'=>12, 
        'desc'=>'Pure, raw honey from local wildflowers.',                               
        'image'=>'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=800',
        'delivery_mode' => 'cargo', 'packaging_type' => 'plastic_free', 'location' => 'Sariyer, Istanbul',
        'seller_name' => 'Bee Natural', 'seller_id' => 106, 'distance' => '12.5 km', 'co2_saved' => '0.2',
        'reviews' => []
    ]
];

// 3. FIND THE PRODUCT
$product = isset($products_db[$product_id]) ? $products_db[$product_id] : null;

// 4. LOGIC & CALCULATIONS
$eco_score = 0;
$eco_badge_class = 'eco-low';
$eco_text = '';
$eco_width = '20%';

if ($product) {
    // A. Translations
    $original_title = $product['title'];
    $display_title = (isset($text['products']) && isset($text['products'][$original_title])) 
                     ? $text['products'][$original_title] 
                     : $original_title;
    
    // B. Calculate Score
    $base_score = 5;
    if ($product['delivery_mode'] == 'bike' || $product['delivery_mode'] == 'walk') $base_score += 3;
    elseif ($product['delivery_mode'] == 'public') $base_score += 2;
    if ($product['packaging_type'] == 'plastic_free') $base_score += 2;
    elseif ($product['packaging_type'] == 'recycled') $base_score += 1;

    $eco_score = min($base_score, 10);
    $eco_width = ($eco_score * 10) . '%';

    // C. Styling Logic
    if ($eco_score >= 8) { $eco_badge_class = 'eco-high'; $eco_text = isset($text['impact_high']) ? $text['impact_high'] : 'Excellent'; }
    elseif ($eco_score >= 6) { $eco_badge_class = 'eco-med'; $eco_text = isset($text['impact_med']) ? $text['impact_med'] : 'Good'; }
    else { $eco_badge_class = 'eco-low'; $eco_text = isset($text['impact_low']) ? $text['impact_low'] : 'Standard'; }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? $display_title : 'Product Not Found'; ?> - Leaf Market</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Arial', sans-serif; background: #fdfbf7; color: #333; }
        .product-container { max-width: 1100px; margin: 40px auto; padding: 30px; display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .product-image img { width: 100%; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-info h1 { font-size: 36px; margin: 0 0 10px 0; color: #1a4d2e; }
        .product-price { font-size: 32px; font-weight: 800; color: #2d2d2d; display: block; margin-bottom: 20px; }
        .eco-dashboard { background: #f8fafc; padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #e2e8f0; }
        .eco-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; color: #1e293b; }
        .eco-bar-bg { height: 12px; background: #e2e8f0; border-radius: 6px; overflow: hidden; margin-bottom: 15px; }
        .eco-bar-fill { height: 100%; border-radius: 6px; transition: width 1s ease-in-out; }
        .eco-high .eco-bar-fill { background: linear-gradient(90deg, #4ade80, #16a34a); }
        .eco-med .eco-bar-fill  { background: linear-gradient(90deg, #facc15, #ca8a04); }
        .eco-low .eco-bar-fill  { background: linear-gradient(90deg, #f87171, #dc2626); }
        .co2-pill { display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; color: #15803d; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px; }
        .seller-card { display: flex; align-items: center; gap: 15px; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 12px; margin-bottom: 25px; text-decoration: none; color: inherit; transition: transform 0.2s; }
        .seller-card:hover { transform: translateY(-2px); border-color: #1a4d2e; }
        .seller-icon { width: 45px; height: 45px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #1a4d2e; font-size: 20px; }
        .stock-badge { display: inline-block; background: #e11d48; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; margin-bottom: 15px; }
        .product-desc { font-size: 16px; line-height: 1.6; color: #666; margin-bottom: 30px; }
        
        /* Buttons */
        .action-buttons { display: flex; gap: 15px; }
        .btn-main { padding: 15px 40px; background: #1a4d2e; color: white; border: none; border-radius: 30px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px; }
        .btn-main:hover { background: #143d23; transform: translateY(-2px); }
        .btn-back { padding: 15px 25px; background: #f3f4f6; color: #333; text-decoration: none; border-radius: 30px; font-weight: bold; display: flex; align-items: center; }

        .reviews-container { grid-column: 1 / -1; margin-top: 40px; padding-top: 30px; border-top: 1px solid #eee; }
        .reviews-title { font-size: 24px; color: #1a4d2e; margin-bottom: 20px; }
        .review-card { background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 15px; border-left: 4px solid #1a4d2e; }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .review-user { font-weight: bold; color: #333; }
        .review-stars { color: #f59e0b; font-size: 14px; }
        .review-comment { color: #555; line-height: 1.5; }
        .verified-badge { background: #dcfce7; color: #15803d; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 8px; font-weight: bold; text-transform: uppercase; }

        @media (max-width: 768px) { .product-container { grid-template-columns: 1fr; padding: 20px; } }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="product-container">
    <?php if ($product): ?>
        <div class="product-image">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($display_title); ?>">
        </div>

        <div class="product-info">
            <span style="color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Local / Organic / Fresh</span>
            <h1><?php echo $display_title; ?></h1>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span class="product-price">
                    <?php echo number_format($product['price'], 2) . ' ' . (isset($text['currency']) ? $text['currency'] : 'TL'); ?>
                </span>
                <?php if($product['stock'] < 5): ?>
                    <div class="stock-badge"><?php echo isset($text['low_stock']) ? $text['low_stock'] : 'Low Stock'; ?>: <?php echo $product['stock']; ?></div>
                <?php endif; ?>
            </div>

            <div class="eco-dashboard <?php echo $eco_badge_class; ?>">
                <div class="eco-header">
                    <span><i class="fas fa-leaf" style="margin-right:5px;"></i> <?php echo isset($text['eco_score_label']) ? $text['eco_score_label'] : 'Sustainability Score'; ?></span>
                    <span><?php echo $eco_score; ?>/10</span>
                </div>
                <div class="eco-bar-bg"><div class="eco-bar-fill" style="width: <?php echo $eco_width; ?>;"></div></div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <small style="color:#64748b; font-weight: 500;"><?php echo $eco_text; ?></small>
                    <div class="co2-pill"><i class="fas fa-cloud"></i> -<?php echo $product['co2_saved']; ?>kg CO₂</div>
                </div>
            </div>

            <a href="seller_profile.php?id=<?php echo $product['seller_id']; ?>" class="seller-card">
                <div class="seller-icon"><i class="fas fa-store"></i></div>
                <div style="flex-grow: 1;">
                    <strong style="display:block; color:#333;"><?php echo $product['seller_name']; ?></strong>
                    <small style="color:#666;"><i class="fas fa-map-marker-alt"></i> <?php echo $product['distance']; ?> away</small>
                </div>
                <div style="text-align:right;">
                    <span style="background:#f3f4f6; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold; color:#1a4d2e;"><?php echo isset($text['seller_profile']) ? $text['seller_profile'] : 'View Profile'; ?> &rarr;</span>
                </div>
            </a>

            <p class="product-desc"><?php echo $product['desc']; ?></p>

            <div class="action-buttons">
                <!-- IMPORTANT: Form to Add to Cart -->
                <form action="cart_action.php" method="POST" style="flex: 1;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($display_title); ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                    <input type="hidden" name="co2" value="<?php echo $product['co2_saved']; ?>">
                    
                    <button type="submit" class="btn-main" style="width: 100%; justify-content: center;">
                        <i class="fas fa-shopping-basket"></i> <?php echo isset($text['add_cart']) ? $text['add_cart'] : 'Add'; ?>
                    </button>
                </form>
                
                <a href="index.php" class="btn-back">&larr; Back</a>
            </div>
        </div>

        <div class="reviews-container">
            <h2 class="reviews-title"><?php echo isset($text['reviews_title']) ? $text['reviews_title'] : 'Reviews'; ?></h2>
            <?php if (!empty($product['reviews'])): ?>
                <?php foreach ($product['reviews'] as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="review-user"><?php echo $review['user']; ?> <span class="verified-badge"><?php echo isset($text['verified_buyer']) ? $text['verified_buyer'] : 'Verified'; ?></span></span>
                            <div class="review-stars">
                                <?php for ($i = 0; $i < 5; $i++) echo ($i < $review['stars']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                            </div>
                        </div>
                        <p class="review-comment">"<?php echo $review['comment']; ?>"</p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">No reviews yet.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
            <h2>Product Not Found</h2><a href="index.php" class="btn-main" style="margin-top: 20px; display: inline-block;">Go Home</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>