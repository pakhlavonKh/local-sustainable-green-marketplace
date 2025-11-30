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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Arial', sans-serif; background: #fdfbf7; color: #333; }
        
        /* Layout */
        .product-container { 
            max-width: 1100px; margin: 40px auto; padding: 30px; 
            display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; 
            background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }
        
        /* Left Side: Image */
        .product-image img { 
            width: 100%; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            object-fit: cover;
        }

        /* Right Side: Info */
        .product-info h1 { font-size: 36px; margin: 0 0 10px 0; color: #1a4d2e; }
        .product-price { font-size: 32px; font-weight: 800; color: #2d2d2d; display: block; margin-bottom: 20px; }
        
        .stock-badge { 
            display: inline-block; background: #e11d48; color: white; 
            padding: 5px 10px; border-radius: 5px; font-size: 12px; 
            font-weight: bold; margin-bottom: 15px; 
        }

        /* Eco Dashboard */
        .eco-dashboard { background: #f8fafc; padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #e2e8f0; }
        .eco-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; color: #1e293b; }
        .eco-bar-bg { height: 12px; background: #e2e8f0; border-radius: 6px; overflow: hidden; margin-bottom: 15px; }
        .eco-bar-fill { height: 100%; border-radius: 6px; transition: width 1s ease-in-out; }
        
        .eco-high .eco-bar-fill { background: linear-gradient(90deg, #4ade80, #16a34a); }
        .eco-med .eco-bar-fill  { background: linear-gradient(90deg, #facc15, #ca8a04); }
        .eco-low .eco-bar-fill  { background: linear-gradient(90deg, #f87171, #dc2626); }
        
        .co2-pill { 
            display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; 
            color: #15803d; font-size: 12px; font-weight: bold; padding: 6px 12px; border-radius: 20px; 
        }

        /* Seller Card */
        .seller-card { 
            display: flex; align-items: center; gap: 15px; padding: 15px; 
            background: #fff; border: 1px solid #eee; border-radius: 12px; 
            margin-bottom: 25px; text-decoration: none; color: inherit; transition: transform 0.2s; 
        }
        .seller-card:hover { transform: translateY(-2px); border-color: #1a4d2e; }
        .seller-icon { 
            width: 45px; height: 45px; background: #f3f4f6; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; color: #1a4d2e; font-size: 20px; 
        }

        .product-desc { font-size: 16px; line-height: 1.6; color: #666; margin-bottom: 30px; }

        /* Buttons */
        .action-buttons { display: flex; gap: 15px; align-items: center; }
        
        .btn-main { 
            padding: 15px 40px; background: #1a4d2e; color: white; border: none; 
            border-radius: 30px; font-weight: bold; font-size: 16px; cursor: pointer; 
            transition: 0.3s; display: flex; align-items: center; gap: 10px; flex-grow: 1; justify-content: center;
            text-decoration: none;
        }
        .btn-main:hover { background: #143d23; transform: translateY(-2px); }
        
        .btn-back { 
            padding: 15px 25px; background: #f3f4f6; color: #333; 
            text-decoration: none; border-radius: 30px; font-weight: bold; 
            display: flex; align-items: center; 
        }

        /* Reviews */
        .reviews-container { grid-column: 1 / -1; margin-top: 40px; padding-top: 30px; border-top: 1px solid #eee; }
        .reviews-title { font-size: 24px; color: #1a4d2e; margin-bottom: 20px; }
        .review-card { background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 15px; border-left: 4px solid #1a4d2e; }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .review-user { font-weight: bold; color: #333; }
        .review-stars { color: #f59e0b; font-size: 14px; }
        .review-comment { color: #555; line-height: 1.5; font-style: italic; }
        .verified-badge { background: #dcfce7; color: #15803d; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 8px; font-weight: bold; text-transform: uppercase; }

        @media (max-width: 768px) { 
            .product-container { grid-template-columns: 1fr; padding: 20px; } 
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

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
            <a href="seller_profile.php?id=<?php echo isset($product['seller_id']) ? $product['seller_id'] : 0; ?>" class="seller-card">
                <div class="seller-icon"><i class="fas fa-store"></i></div>
                <div style="flex-grow: 1;">
                    <strong style="display:block; color:#333;">
                        <?php echo isset($product['seller_name']) ? $product['seller_name'] : 'Leaf Market'; ?>
                    </strong>
                    <small style="color:#666;">
                        <i class="fas fa-map-marker-alt"></i> <?php echo isset($product['distance']) ? $product['distance'] : 'Local'; ?> away
                    </small>
                </div>
                <div style="text-align:right;">
                    <span style="background:#f3f4f6; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold; color:#1a4d2e;">
                        <?php echo isset($text['seller_profile']) ? $text['seller_profile'] : 'View'; ?> &rarr;
                    </span>
                </div>
            </a>

            <p class="product-desc"><?php echo isset($product['desc']) ? $product['desc'] : 'No description available.'; ?></p>

            <div class="action-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- LOGGED IN: Add to Cart Form -->
                    <form action="cart_action.php" method="POST" style="flex: 1;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($display_title); ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                        <input type="hidden" name="co2" value="<?php echo isset($product['co2_saved']) ? $product['co2_saved'] : 0; ?>">
                        
                        <button type="submit" class="btn-main">
                            <i class="fas fa-shopping-basket"></i> <?php echo isset($text['add_cart']) ? $text['add_cart'] : 'Add'; ?>
                        </button>
                    </form>
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