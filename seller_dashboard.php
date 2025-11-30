<?php
session_start();
require_once 'db_connect.php';
require_once 'lang_config.php';

// 1. SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$db = getDBConnection();
$my_products = [];

if ($db) {
    $productsCollection = $db->products;
    // Fetch products linked to this seller's ID (string)
    $seller_id = (string)$_SESSION['user_id'];
    $my_products = $productsCollection->find(
        ['seller_id' => $seller_id], 
        ['sort' => ['id' => -1]]
    )->toArray();
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #fdfbf7; color: #333; }
        .seller-container { max-width: 1200px; margin: 60px auto; padding: 0 40px; display: grid; grid-template-columns: 300px 1fr; gap: 60px; }
        
        /* SIDEBAR */
        .seller-sidebar { padding-right: 40px; border-right: 1px solid #eee; display: flex; flex-direction: column; align-items: center; text-align: center; }
        .user-avatar { width: 120px; height: 120px; background-color: #1a4d2e; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: 48px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(26, 77, 46, 0.2); }
        .user-name { font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 5px; color: #000; }
        .user-role { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #1a4d2e; font-weight: 700; margin-bottom: 30px; }
        .btn-action { display: block; width: 100%; padding: 15px; background: #1a4d2e; color: white; text-align: center; text-decoration: none; font-weight: bold; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; margin-bottom: 10px; border: none; cursor: pointer; border-radius: 4px; }
        .btn-action:hover { background: #143d23; }
        .btn-logout { border: 1px solid #333; background: white; color: #333; margin-top: 30px; }
        .btn-logout:hover { background: #000; color: white; }

        /* CONTENT */
        .section-title { font-family: 'Playfair Display', serif; font-size: 32px; margin: 0 0 30px 0; color: #000; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }
        .seller-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: relative; border: 1px solid #f0f0f0; transition: transform 0.2s; }
        .seller-card:hover { transform: translateY(-3px); }
        .sc-img { height: 160px; background-size: cover; background-position: center; }
        .sc-info { padding: 15px; }
        .sc-title { font-weight: bold; display: block; margin-bottom: 5px; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sc-price { color: #1a4d2e; font-weight: bold; }
        .btn-delete { position: absolute; top: 10px; right: 10px; background: white; color: #ef4444; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.2s; }
        .btn-delete:hover { background: #ef4444; color: white; }
        
        /* MODAL */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
        .modal-content { background: white; width: 450px; margin: 80px auto; padding: 40px; border-radius: 10px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .close-btn { position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #999; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; color: #555; }
        .form-input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: 'Lato', sans-serif; }
        
        @media (max-width: 900px) { .seller-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="seller-container">
    <div class="seller-sidebar">
        <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
        <h1 class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <div class="user-role">Verified Seller</div>
        <button onclick="document.getElementById('addModal').style.display='block'" class="btn-action"><i class="fas fa-plus"></i> List New Product</button>
        <a href="logout.php" class="btn-action btn-logout">Sign Out</a>
    </div>

    <div class="seller-content">
        <h2 class="section-title">My Inventory (<?php echo count($my_products); ?>)</h2>
        <?php if (empty($my_products)): ?>
            <div style="text-align:center; padding: 50px; background: #fff; border-radius: 10px; border: 1px solid #eee;">
                <i class="fas fa-box-open" style="font-size: 40px; color: #ddd; margin-bottom: 20px;"></i>
                <p style="color:#666;">You haven't listed any products yet.</p>
                <button onclick="document.getElementById('addModal').style.display='block'" style="color:#1a4d2e; background:none; border:none; font-weight:bold; text-decoration:underline; cursor:pointer;">List your first item</button>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach($my_products as $p): ?>
                    <div class="seller-card">
                        <div class="sc-img" style="background-image: url('<?php echo $p['image']; ?>');"></div>
                        <a href="seller_action.php?action=delete&id=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Delete this product?')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        <div class="sc-info">
                            <span class="sc-title"><?php echo $p['title']; ?></span>
                            <span class="sc-price"><?php echo number_format($p['price'], 2); ?> TL</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
        <h2 style="margin-top:0; color:#1a4d2e; font-family:'Playfair Display', serif;">List New Item</h2>
        <form action="seller_action.php" method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label class="form-label">Product Name</label><input type="text" name="title" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Category</label>
                <select name="category" class="form-input">
                    <option value="fresh_produce">Fresh Produce</option>
                    <option value="dairy_eggs">Dairy & Eggs</option>
                    <option value="bakery">Bakery</option>
                    <option value="pantry">Pantry</option>
                    <option value="beverages">Beverages</option>
                    <option value="home_garden">Home & Garden</option>
                </select>
            </div>
            <div style="display:flex; gap:20px;">
                <div class="form-group" style="flex:1;"><label class="form-label">Price</label><input type="number" name="price" step="0.01" class="form-input" required></div>
                <div class="form-group" style="flex:1;"><label class="form-label">Stock</label><input type="number" name="stock" class="form-input" required></div>
            </div>
            <div class="form-group"><label class="form-label">Unsplash Image ID</label><input type="text" name="image_id" class="form-input" placeholder="e.g. 1560806887-1e4cd0b6cbd6"></div>
            <button type="submit" class="btn-action">Publish Product</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>