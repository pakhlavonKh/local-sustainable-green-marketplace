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
    try {
        $productsCollection = $db->products;
        // Fetch products linked to this seller's ID (string)
        $seller_id = (string)$_SESSION['user_id'];
        $my_products = $productsCollection->find(
            ['seller_id' => $seller_id], 
            ['sort' => ['id' => -1]]
        )->toArray();
    } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
        error_log('MongoDB timeout in seller_dashboard.php: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('MongoDB error in seller_dashboard.php: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="seller-page-body">
         


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
            <?php require_once 'product_card.php'; ?>
            <div class="product-grid">
                <?php foreach($my_products as $p): 
                    // Convert MongoDB document to array if needed
                    $product_array = is_array($p) ? $p : (array)$p;
                    renderProductCard($product_array, [
                        'show_wishlist' => false,
                        'show_add_to_cart' => false,
                        'show_delete' => true,
                        'is_owner' => true,
                        'redirect_after_delete' => 'seller_dashboard.php'
                    ]);
                endforeach; ?>
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

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h2 style="margin-top:0; color:#1a4d2e; font-family:'Playfair Display', serif;">Edit Product</h2>
        <form action="seller_action.php" method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="product_id" id="edit_product_id">
            <div class="form-group"><label class="form-label">Product Name</label><input type="text" name="title" id="edit_title" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Category</label>
                <select name="category" id="edit_category" class="form-input">
                    <option value="fresh_produce">Fresh Produce</option>
                    <option value="dairy_eggs">Dairy & Eggs</option>
                    <option value="bakery">Bakery</option>
                    <option value="pantry">Pantry</option>
                    <option value="beverages">Beverages</option>
                    <option value="home_garden">Home & Garden</option>
                </select>
            </div>
            <div style="display:flex; gap:20px;">
                <div class="form-group" style="flex:1;"><label class="form-label">Price</label><input type="number" name="price" id="edit_price" step="0.01" class="form-input" required></div>
                <div class="form-group" style="flex:1;"><label class="form-label">Stock</label><input type="number" name="stock" id="edit_stock" class="form-input" required></div>
            </div>
            <div class="form-group"><label class="form-label">Unsplash Image ID (leave empty to keep current)</label><input type="text" name="image_id" id="edit_image_id" class="form-input" placeholder="e.g. 1560806887-1e4cd0b6cbd6"></div>
            <button type="submit" class="btn-action">Update Product</button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, product) {
    document.getElementById('edit_product_id').value = id;
    document.getElementById('edit_title').value = product.title;
    document.getElementById('edit_category').value = product.category;
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_stock').value = product.stock;
    document.getElementById('edit_image_id').value = '';
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>