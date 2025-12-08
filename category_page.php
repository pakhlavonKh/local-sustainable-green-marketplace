<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

// CHECK IF USER IS SELLER
$is_seller = (isset($_SESSION['role']) && $_SESSION['role'] === 'seller');
$my_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; background: #fdfbf7; margin: 0; color: #333; }
        
        /* Header */
        .simple-header { background: #fff; padding: 40px 20px; text-align: center; border-bottom: 1px solid #eee; margin-bottom: 40px; position: relative; }
        .simple-header h1 { margin: 0; font-family: 'Playfair Display', serif; font-size: 36px; color: #1a4d2e; }
        .breadcrumb { margin-top: 10px; font-size: 14px; color: #666; }
        
        /* Add Product Button (Only for Sellers) */
        .seller-controls { margin-top: 20px; }
        .btn-add-product { background: #1a4d2e; color: white; border: none; padding: 10px 20px; border-radius: 30px; font-weight: bold; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-add-product:hover { background: #143d23; }

        /* Grid */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px 60px 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; }

        /* Product Card */
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.2s; position: relative; display: flex; flex-direction: column; border: 1px solid #eee; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .p-img-wrapper { height: 220px; position: relative; overflow: hidden; }
        .p-img { width: 100%; height: 100%; background-size: cover; background-position: center; transition: transform 0.5s; }
        .product-card:hover .p-img { transform: scale(1.05); }
        
        /* Delete Button (Only for Owner) */
        .btn-delete-overlay { position: absolute; top: 10px; right: 10px; background: white; color: #ef4444; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 10; cursor: pointer; transition: 0.2s; text-decoration: none; }
        .btn-delete-overlay:hover { background: #ef4444; color: white; }

        .p-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .p-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .p-seller { font-size: 12px; color: #888; margin-bottom: 10px; }
        .p-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .p-price { font-size: 18px; font-weight: 800; color: #1a4d2e; }
        
        .add-btn { background: #f3f4f6; color: #333; border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; }
        .add-btn:hover { background: #1a4d2e; color: white; }
        
        .empty-msg { text-align: center; grid-column: 1 / -1; padding: 50px; color: #666; font-size: 18px; }

        /* Modal Styles */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background: white; width: 450px; margin: 80px auto; padding: 40px; border-radius: 10px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .close-btn { position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #999; }
        .form-group { margin-bottom: 15px; }
        .form-label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; color: #555; }
        .form-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: 'Lato', sans-serif; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="simple-header">
    <h1><?php echo $page_title; ?></h1>
    <div class="breadcrumb">
        <a href="index.php">Home</a> / <?php echo $page_title; ?>
    </div>

    <!-- SELLER ONLY: Add Product Button -->
    <?php if ($is_seller): ?>
    <div class="seller-controls">
        <button onclick="document.getElementById('addModal').style.display='block'" class="btn-add-product">
            <i class="fas fa-plus"></i> Add Product to <?php echo $page_title; ?>
        </button>
    </div>
    <?php endif; ?>
</div>

<div class="container">
    <div class="product-grid">
        <?php if(count($filtered_products) > 0): ?>
            <?php foreach($filtered_products as $p): ?>
                <?php
                    $id = $p['id'];
                    $display_title = ($_SESSION['lang'] == 'tr' && !empty($p['title_tr'])) ? $p['title_tr'] : $p['title'];
                    $price = $p['price'];
                    $img = $p['image'];
                    // Check ownership
                    $is_mine = ($is_seller && isset($p['seller_id']) && (string)$p['seller_id'] === (string)$my_id);
                ?>
                
                <div class="product-card">
                    <a href="product_detail.php?id=<?php echo $id; ?>" style="text-decoration:none; color:inherit;">
                        <div class="p-img-wrapper">
                            <div class="p-img" style="background-image: url('<?php echo $img; ?>');"></div>
                            
                            <!-- SELLER ONLY: Delete Button -->
                            <?php if ($is_mine): ?>
                                <a href="seller_action.php?action=delete&id=<?php echo $id; ?>&redirect=category_page.php?category=<?php echo $cat_slug; ?>" 
                                   class="btn-delete-overlay" 
                                   onclick="return confirm('Delete this product?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>

                            <?php if($p['stock'] < 5): ?>
                                <span style="position:absolute; top:10px; left:10px; background:#e11d48; color:white; font-size:10px; padding:4px 8px; border-radius:4px; font-weight:bold;">Low Stock</span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div class="p-info">
                        <div>
                            <span class="p-title"><?php echo htmlspecialchars($display_title); ?></span>
                            <span class="p-seller">by <?php echo $p['seller_name']; ?></span>
                        </div>
                        
                        <div class="p-footer">
                            <span class="p-price"><?php echo number_format($price, 2); ?> TL</span>
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

<!-- SELLER MODAL -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
        <h2 style="margin-top:0; color:#1a4d2e; font-family:'Playfair Display', serif;">List New Item</h2>
        <form action="seller_action.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="redirect" value="category_page.php?category=<?php echo $cat_slug; ?>">
            
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="title" class="form-input" required placeholder="e.g. Fresh Basil">
            </div>
            <!-- Auto-select current category -->
            <input type="hidden" name="category" value="<?php echo ($cat_slug == 'all') ? 'fresh_produce' : $cat_slug; ?>">
            
            <div style="display:flex; gap:20px;">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Price (TL)</label>
                    <input type="number" name="price" step="0.01" class="form-input" required placeholder="0.00">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-input" required placeholder="10">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Unsplash Image ID</label>
                <input type="text" name="image_id" class="form-input" required placeholder="e.g. 1618331835717-801e976710b2">
                <small style="color:#888;">Try: 1618331835717-801e976710b2</small>
            </div>
            <button type="submit" class="btn-add-product" style="width:100%; justify-content:center;">Publish Now</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>