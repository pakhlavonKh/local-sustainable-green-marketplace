<?php
require_once 'lang_config.php';
require_once 'data_products.php'; 

// 1. Get Category from URL
$cat_slug = isset($_GET['category']) ? $_GET['category'] : 'all';

// 2. Map Slug to Display Name
$cat_titles = [
    'fresh_produce' => isset($text['cat_fresh']) ? $text['cat_fresh'] : 'Fresh Produce',
    'dairy_eggs'    => isset($text['cat_dairy']) ? $text['cat_dairy'] : 'Dairy & Eggs',
    'bakery'        => isset($text['cat_bakery']) ? $text['cat_bakery'] : 'Bakery',
    'pantry'        => isset($text['cat_pantry']) ? $text['cat_pantry'] : 'Pantry',
    'beverages'     => isset($text['cat_bev'])    ? $text['cat_bev']    : 'Beverages',
    'home_garden'   => isset($text['cat_home'])   ? $text['cat_home']   : 'Home & Garden',
    'all'           => isset($text['shop_all'])   ? $text['shop_all']   : 'All Products'
];

$page_title = isset($cat_titles[$cat_slug]) ? $cat_titles[$cat_slug] : 'Category';

// 3. Filter Products
$filtered_products = [];
if (isset($products_db) && is_array($products_db)) {
    foreach($products_db as $p) {
        if ($cat_slug == 'all' || (isset($p['category']) && $p['category'] == $cat_slug)) {
            $filtered_products[] = $p;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - Leaf Market</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background: #fdfbf7; margin: 0; color: #333; }
        
        /* 1. SIMPLE HEADER */
        .simple-header {
            background: #fff;
            padding: 40px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
            margin-bottom: 40px;
        }
        .simple-header h1 {
            margin: 0;
            font-size: 32px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a4d2e; /* Dark Green */
        }
        .breadcrumb { margin-top: 10px; font-size: 14px; color: #666; }
        .breadcrumb a { color: #666; text-decoration: none; }

        /* 2. PRODUCT GRID */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px 60px 20px; }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        /* 3. PRODUCT CARD (Clean Style) */
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            border: 1px solid #eee;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        .p-img-wrapper { 
            height: 220px; 
            overflow: hidden; 
            position: relative; 
        }
        .p-img {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s;
        }
        .product-card:hover .p-img { transform: scale(1.05); }

        .p-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .p-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; display: block; color: #333; }
        .p-seller { font-size: 12px; color: #888; margin-bottom: 10px; display: block; }
        
        .p-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .p-price { font-size: 18px; font-weight: 800; color: #1a4d2e; }
        
        /* Add Button */
        .add-btn {
            background: #f3f4f6; color: #333; border: none; width: 35px; height: 35px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;
        }
        .add-btn:hover { background: #1a4d2e; color: white; }

        .empty-msg { text-align: center; grid-column: 1 / -1; padding: 50px; color: #666; font-size: 18px; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- 1. CLEAN TITLE SECTION -->
<div class="simple-header">
    <h1><?php echo $page_title; ?></h1>
    <div class="breadcrumb">
        <a href="index.php"><?php echo isset($text['nav_home']) ? $text['nav_home'] : 'Home'; ?></a> / <?php echo $page_title; ?>
    </div>
</div>

<!-- 2. PRODUCT LIST -->
<div class="container">
    <div class="product-grid">
        <?php if(count($filtered_products) > 0): ?>
            <?php foreach($filtered_products as $p): ?>
                <?php
                    $id = $p['id'];
                    $display_title = ($_SESSION['lang'] == 'tr' && !empty($p['title_tr'])) ? $p['title_tr'] : $p['title'];
                    $price = $p['price'];
                    $img = $p['image'];
                ?>
                
                <div class="product-card">
                    <a href="product_detail.php?id=<?php echo $id; ?>" style="text-decoration:none; color:inherit;">
                        <div class="p-img-wrapper">
                            <div class="p-img" style="background-image: url('<?php echo $img; ?>');"></div>
                            <?php if($p['stock'] < 5): ?>
                                <span style="position:absolute; top:10px; left:10px; background:#e11d48; color:white; font-size:10px; padding:4px 8px; border-radius:4px; font-weight:bold;">
                                    <?php echo isset($text['low_stock']) ? $text['low_stock'] : 'Low Stock'; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div class="p-info">
                        <a href="product_detail.php?id=<?php echo $id; ?>" style="text-decoration:none; color:inherit;">
                            <span class="p-title"><?php echo htmlspecialchars($display_title); ?></span>
                            <span class="p-seller">by <?php echo $p['seller_name']; ?></span>
                        </a>
                        
                        <div class="p-footer">
                            <span class="p-price">
                                <?php echo number_format($price, 2); ?> 
                                <?php echo isset($text['currency']) ? $text['currency'] : 'TL'; ?>
                            </span>
                            
                            <form action="cart_action.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="title" value="<?php echo htmlspecialchars($display_title); ?>">
                                <input type="hidden" name="price" value="<?php echo $price; ?>">
                                <input type="hidden" name="image" value="<?php echo $img; ?>">
                                <input type="hidden" name="co2" value="<?php echo $p['co2_saved']; ?>">
                                <button type="submit" class="add-btn" title="Add to Cart">+</button>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-msg">
                <i class="fas fa-leaf" style="font-size: 40px; margin-bottom: 20px; display:block; opacity:0.3; color: #1a4d2e;"></i>
                <p>No products found in this category.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>