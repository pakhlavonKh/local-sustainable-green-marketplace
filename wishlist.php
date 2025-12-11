<?php
session_start();
require_once 'db_connect.php';
require_once 'data_products.php';
require_once 'lang_config.php';

$db = getDBConnection();
$wishlist_products = [];
$wishlist_ids = [];

// Get wishlist from session or database
if (isset($_SESSION['user_id']) && $db) {
    try {
        $usersCollection = $db->users;
        $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
        
        if ($user && isset($user['wishlist'])) {
            $wishlist_ids = is_array($user['wishlist']) ? $user['wishlist'] : [];
        }
    } catch (Exception $e) {
        error_log('Wishlist fetch error: ' . $e->getMessage());
    }
}

// Fallback to session wishlist (for non-logged-in users or DB errors)
if (empty($wishlist_ids) && isset($_SESSION['wishlist'])) {
    $wishlist_ids = $_SESSION['wishlist'];
}

// Get products from database or local array
if (!empty($wishlist_ids)) {
    if ($db) {
        try {
            $productsCollection = $db->products;
            $cursor = $productsCollection->find(['id' => ['$in' => $wishlist_ids]]);
            foreach ($cursor as $doc) {
                $wishlist_products[] = (array)$doc;
            }
        } catch (Exception $e) {
            error_log('Wishlist products fetch error: ' . $e->getMessage());
        }
    }
    
    // Fallback to local products
    if (empty($wishlist_products)) {
        foreach ($products_db as $p) {
            if (in_array($p['id'], $wishlist_ids)) {
                $wishlist_products[] = $p;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title>My Favorites - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="wishlist-page-body">
        
<?php include 'navbar.php'; ?>

<div class="wishlist-container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-heart" style="color: #e53e3e;"></i> My Favorites</h1>
        <p class="page-subtitle"><?php echo count($wishlist_products); ?> items in your wishlist</p>
    </div>

    <?php if (empty($wishlist_products)): ?>
        <div class="empty-state">
            <i class="far fa-heart"></i>
            <h2>Your wishlist is empty</h2>
            <p>Start adding products you love!</p>
            <a href="category_page.php?category=all" class="btn-browse">Browse Products</a>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($wishlist_products as $product): 
                $title = isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' && !empty($product['title_tr']) 
                         ? $product['title_tr'] 
                         : $product['title'];
            ?>
                <div class="product-card">
                    <div class="product-card-img" style="background-image: url('<?php echo htmlspecialchars($product['image']); ?>');"></div>
                    <div class="product-card-content">
                        <h3 class="product-card-title"><?php echo htmlspecialchars($title); ?></h3>
                        <div class="product-card-price">₺<?php echo number_format($product['price'], 2); ?></div>
                        <div class="product-card-meta">
                            <span><i class="fas fa-leaf"></i> <?php echo $product['co2_saved']; ?> kg CO₂</span>
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn-view" style="flex: 1; background: #1a4d2e; color: white; padding: 10px; text-align: center; text-decoration: none; border-radius: 4px; font-size: 14px;">View</a>
                            <button onclick="removeFromWishlist(<?php echo $product['id']; ?>)" class="btn-remove" style="background: #ef4444; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
function removeFromWishlist(productId) {
    if (!confirm('Remove this item from your favorites?')) return;
    
    fetch('wishlist_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=remove&product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>

</body>
</html>
