<?php
// 1. SETUP & DATA LOADING
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'lang_config.php';   // Load translations
require_once 'data_products.php'; // Load the database of products

// 2. GET PRODUCT ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 3. FIND PRODUCT IN DATABASE
$product = isset($products_db[$product_id]) ? $products_db[$product_id] : null;

// 4. CALCULATE ECO SCORE & VARIABLES
$eco_score = 0;
$eco_badge_class = 'eco-low';
$eco_text = '';
$eco_width = '20%';
$display_title = 'Unknown Product';

if ($product) {
    // A. Translation Logic
    // Checks if we are in Turkish mode AND if the product has a Turkish title
    $display_title = ($_SESSION['lang'] == 'tr' && !empty($product['title_tr'])) 
                     ? $product['title_tr'] 
                     : $product['title'];
    
    // B. Calculate Eco Score
    $base_score = 5;
    // Delivery Impact
    if (isset($product['delivery_mode'])) {
        if ($product['delivery_mode'] == 'bike' || $product['delivery_mode'] == 'walk') $base_score += 3;
        elseif ($product['delivery_mode'] == 'public') $base_score += 2;
    }
    // Packaging Impact
    if (isset($product['packaging_type'])) {
        if ($product['packaging_type'] == 'plastic_free') $base_score += 2;
        elseif ($product['packaging_type'] == 'recycled') $base_score += 1;
    }

    $eco_score = min($base_score, 10);
    $eco_width = ($eco_score * 10) . '%';

    // C. Determine Badge Style
    if ($eco_score >= 8) { 
        $eco_badge_class = 'eco-high'; 
        $eco_text = isset($text['impact_high']) ? $text['impact_high'] : 'Excellent'; 
    } elseif ($eco_score >= 6) { 
        $eco_badge_class = 'eco-med'; 
        $eco_text = isset($text['impact_med']) ? $text['impact_med'] : 'Good'; 
    } else { 
        $eco_badge_class = 'eco-low'; 
        $eco_text = isset($text['impact_low']) ? $text['impact_low'] : 'Standard'; 
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($display_title) : 'Product Not Found'; ?> - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="product-detail-body">
        


<div class="product-container">
    <?php if ($product): ?>
        <!-- Left: Product Image -->
        <div class="product-image">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($display_title); ?>">
        </div>

        <!-- Right: Info & Actions -->
        <div class="product-info">
            <span style="color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                <?php echo isset($text['cat_' . $product['category']]) ? $text['cat_' . $product['category']] : 'Organic'; ?>
            </span>
            <h1><?php echo htmlspecialchars($display_title); ?></h1>
            
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span class="product-price">
                    <?php echo number_format($product['price'], 2) . ' ' . (isset($text['currency']) ? $text['currency'] : 'TL'); ?>
                </span>
                <?php if(isset($product['stock']) && $product['stock'] < 5): ?>
                    <div class="stock-badge">
                        <?php echo isset($text['low_stock']) ? $text['low_stock'] : 'Low Stock'; ?>: <?php echo $product['stock']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ECO DASHBOARD -->
            <div class="eco-dashboard <?php echo $eco_badge_class; ?>">
                <div class="eco-header">
                    <span><i class="fas fa-leaf" style="margin-right:5px;"></i> <?php echo isset($text['eco_score_label']) ? $text['eco_score_label'] : 'Sustainability Score'; ?></span>
                    <span><?php echo $eco_score; ?>/10</span>
                </div>
                <div class="eco-bar-bg"><div class="eco-bar-fill" style="width: <?php echo $eco_width; ?>;"></div></div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <small style="color:#64748b; font-weight: 500;"><?php echo $eco_text; ?></small>
                    <div class="co2-pill">
                        <i class="fas fa-cloud"></i> -<?php echo isset($product['co2_saved']) ? $product['co2_saved'] : '0.1'; ?>kg CO₂
                    </div>
                </div>
            </div>

            <!-- SELLER CARD -->
            <?php if (isset($product['seller_id']) && !empty($product['seller_id'])): ?>
                <a href="seller_profile.php?id=<?php echo htmlspecialchars($product['seller_id']); ?>" class="seller-card">
                    <div class="seller-icon"><i class="fas fa-store"></i></div>
                    <div style="flex-grow: 1;">
                        <strong style="display:block; color:#333;">
                            <?php echo isset($product['seller_name']) ? htmlspecialchars($product['seller_name']) : 'Seller'; ?>
                        </strong>
                        <small style="color:#666;">
                            <i class="fas fa-map-marker-alt"></i> <?php echo isset($product['distance']) ? htmlspecialchars($product['distance']) : 'Local'; ?> away
                        </small>
                    </div>
                    <div style="text-align:right;">
                        <span style="background:#f3f4f6; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold; color:#1a4d2e;">
                            <?php echo isset($text['seller_profile']) ? $text['seller_profile'] : 'View Profile'; ?> &rarr;
                        </span>
                    </div>
                </a>
            <?php else: ?>
                <div class="seller-card" style="cursor: default; opacity: 0.7;">
                    <div class="seller-icon"><i class="fas fa-store"></i></div>
                    <div style="flex-grow: 1;">
                        <strong style="display:block; color:#333;">Leaf Market</strong>
                        <small style="color:#666;">Direct from marketplace</small>
                    </div>
                </div>
            <?php endif; ?>

            <p class="product-desc"><?php echo isset($product['desc']) ? $product['desc'] : 'No description available.'; ?></p>

            <!-- Stock Information -->
            <div style="margin: 20px 0; padding: 15px; background: <?php echo ($product['stock'] > 10) ? '#f0fdf4' : (($product['stock'] > 0) ? '#fef3c7' : '#fee2e2'); ?>; border-radius: 10px; border-left: 4px solid <?php echo ($product['stock'] > 10) ? '#22c55e' : (($product['stock'] > 0) ? '#f59e0b' : '#dc2626'); ?>;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-box" style="color: <?php echo ($product['stock'] > 10) ? '#166534' : (($product['stock'] > 0) ? '#92400e' : '#991b1b'); ?>; font-size: 20px;"></i>
                    <div>
                        <?php if ($product['stock'] > 10): ?>
                            <strong style="color: #166534;">In Stock</strong>
                            <span style="color: #166534; font-size: 14px;"> - <?php echo $product['stock']; ?> available</span>
                        <?php elseif ($product['stock'] > 0): ?>
                            <strong style="color: #92400e;">Only <?php echo $product['stock']; ?> left in stock!</strong>
                            <span style="color: #92400e; font-size: 14px;"> - Order soon</span>
                        <?php else: ?>
                            <strong style="color: #991b1b;">Out of Stock</strong>
                            <span style="color: #991b1b; font-size: 14px;"> - Currently unavailable</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <?php if ($product['stock'] > 0 && isset($_SESSION['user_id'])): ?>
                    <!-- LOGGED IN & IN STOCK: Add to Cart Form with Quantity -->
                    <form action="cart_action.php" method="POST" style="flex: 1; display: flex; gap: 15px; align-items: center;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($display_title); ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                        <input type="hidden" name="co2" value="<?php echo isset($product['co2_saved']) ? $product['co2_saved'] : 0; ?>">
                        <input type="hidden" name="stock" value="<?php echo $product['stock']; ?>">
                        
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label style="font-weight: 600; color: #333;">Qty:</label>
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" 
                                   style="width: 80px; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; font-weight: 600; text-align: center;">
                        </div>
                        
                        <button type="submit" class="btn-main" style="flex: 1;">
                            <i class="fas fa-shopping-basket"></i> <?php echo isset($text['add_cart']) ? $text['add_cart'] : 'Add to Cart'; ?>
                        </button>
                    </form>
                <?php elseif ($product['stock'] == 0): ?>
                    <!-- OUT OF STOCK -->
                    <button class="btn-main" disabled style="flex: 1; opacity: 0.5; cursor: not-allowed; background: #9ca3af;">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </button>
                <?php else: ?>
                    <!-- NOT LOGGED IN: Redirect to Login (preserving return path) -->
                    <a href="login.php?redirect=product_detail.php?id=<?php echo $product_id; ?>" class="btn-main">
                        <i class="fas fa-lock"></i> <?php echo isset($text['nav_login']) ? $text['nav_login'] : 'Login to Buy'; ?>
                    </a>
                <?php endif; ?>
                
                <a href="index.php" class="btn-back">&larr; Back</a>
            </div>
        </div>

        <!-- REVIEWS SECTION -->
        <div class="reviews-container">
            <h2 class="reviews-title"><?php echo isset($text['reviews_title']) ? $text['reviews_title'] : 'Reviews'; ?></h2>
            
            <?php if (isset($product['reviews']) && !empty($product['reviews'])): ?>
                <?php foreach ($product['reviews'] as $review): ?>
                    <?php $review = (array)$review; // Ensure array access ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="review-user">
                                <?php echo isset($review['user']) ? htmlspecialchars($review['user']) : 'Customer'; ?>
                                <span class="verified-badge"><?php echo isset($text['verified_buyer']) ? $text['verified_buyer'] : 'Verified'; ?></span>
                            </span>
                            <div class="review-stars">
                                <?php 
                                $stars = isset($review['stars']) ? (int)$review['stars'] : 5;
                                for ($i = 0; $i < 5; $i++) echo ($i < $stars) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; 
                                ?>
                            </div>
                        </div>
                        <p class="review-comment">"<?php echo isset($review['comment']) ? htmlspecialchars($review['comment']) : ''; ?>"</p>
                        
                        <!-- Seller Reply Display -->
                        <?php if(isset($review['reply'])): ?>
                            <div style="background:#f0fdf4; padding:10px; margin-top:10px; border-radius:8px; border-left:3px solid #166534;">
                                <strong style="color:#166534; font-size:11px; text-transform:uppercase;">Seller Response:</strong>
                                <p style="margin:5px 0 0 0; font-size:14px; color:#333; font-style:normal;"><?php echo htmlspecialchars($review['reply']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">No reviews yet. Be the first to review this sustainable product!</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
            <h2>Product Not Found</h2>
            <a href="index.php" class="btn-main" style="margin-top: 20px; display: inline-block;">Go Home</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>