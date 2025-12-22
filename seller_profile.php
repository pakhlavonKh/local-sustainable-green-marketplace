<?php
session_start();
require_once 'lang_config.php';
require_once 'db_connect.php';

// Get Seller ID from URL (can be username or user_id)
$seller_identifier = isset($_GET['id']) ? $_GET['id'] : null;

$db = getDBConnection();
$seller = null;
$seller_products = [];

if ($seller_identifier && $db) {
    try {
        $usersCollection = $db->users;
        $productsCollection = $db->products;
        
        // Build query to find seller
        $query = ['role' => 'seller'];
        
        // Check if identifier is a valid ObjectId format (24-character hex string)
        if (strlen($seller_identifier) === 24 && ctype_xdigit($seller_identifier)) {
            try {
                $query['_id'] = new MongoDB\BSON\ObjectId($seller_identifier);
            } catch (Exception $e) {
                // If conversion fails, treat as username
                $query['username'] = $seller_identifier;
            }
        } else {
            // Not a valid ObjectId format, treat as username
            $query['username'] = $seller_identifier;
        }
        
        $seller = $usersCollection->findOne($query);
        
        // If not found and we have an identifier, try alternative searches
        if (!$seller && $seller_identifier) {
            // Search all sellers and match by ObjectId string conversion
            $allSellers = $usersCollection->find(['role' => 'seller'])->toArray();
            foreach ($allSellers as $s) {
                if ((string)$s['_id'] === $seller_identifier) {
                    $seller = $s;
                    break;
                }
            }
            
            // If still not found, check if there are products with this seller_id
            if (!$seller) {
                $product = $productsCollection->findOne(['seller_id' => $seller_identifier]);
                if ($product) {
                    // Create a minimal seller profile from product data
                    $seller = [
                        '_id' => $seller_identifier,
                        'username' => $product['seller_name'] ?? 'Seller',
                        'role' => 'seller',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        
        // If seller found, get their products
        if ($seller) {
            $seller_id_str = is_string($seller['_id']) ? $seller['_id'] : (string)$seller['_id'];
            $seller_products = $productsCollection->find(
                ['seller_id' => $seller_id_str],
                ['sort' => ['id' => -1]]
            )->toArray();
        }
    } catch (Exception $e) {
        error_log('Seller profile error: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $seller ? ($seller['name'] ?? $seller['username'] ?? 'Seller') : 'Seller'; ?> - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="seller-profile-body">
        


<?php include 'navbar.php'; ?>

<?php if ($seller): ?>
    <?php 
        $seller_name = $seller['name'] ?? $seller['username'] ?? 'Seller';
        $seller_initials = strtoupper(substr($seller_name, 0, 2));
    ?>
    <div class="seller-profile-header">
        <div class="seller-profile-banner">
            <div class="seller-profile-avatar">
                <?php echo $seller_initials; ?>
            </div>
        </div>
        
        <div class="seller-profile-info">
            <h1 class="seller-profile-name"><?php echo htmlspecialchars($seller_name); ?></h1>
            <div class="seller-profile-meta">
                <span class="meta-badge">
                    <i class="fas fa-check-circle"></i> 
                    <?php echo isset($text['verified_seller']) ? $text['verified_seller'] : 'Verified Seller'; ?>
                </span>
                <span class="meta-item">
                    <i class="fas fa-box"></i> 
                    <?php echo count($seller_products); ?> <?php echo count($seller_products) == 1 ? 'Product' : 'Products'; ?>
                </span>
                <span class="meta-item">
                    <i class="fas fa-calendar-alt"></i> 
                    Member since <?php echo date('M Y', strtotime($seller['created_at'] ?? 'now')); ?>
                </span>
            </div>
            
            <?php if (isset($seller['bio'])): ?>
                <p class="seller-profile-bio"><?php echo htmlspecialchars($seller['bio']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container" style="padding: 40px 20px;">
        <h2 class="section-title">
            <i class="fas fa-shopping-bag"></i> 
            <?php echo isset($text['seller_products']) ? $text['seller_products'] : 'Products from this seller'; ?>
        </h2>
        
        <?php if (empty($seller_products)): ?>
            <div class="empty-state" style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin-top: 20px;">
                <i class="fas fa-box-open" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                <p style="color: #666; font-size: 16px;">This seller hasn't listed any products yet.</p>
            </div>
        <?php else: ?>
            <?php require_once 'product_card.php'; ?>
            <div class="product-grid">
                <?php 
                foreach($seller_products as $p) {
                    $product_array = is_array($p) ? $p : (array)$p;
                    renderProductCard($product_array, [
                        'show_wishlist' => true,
                        'show_add_to_cart' => true
                    ]);
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="error-container">
        <div class="error-content">
            <i class="fas fa-user-slash" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
            <h2 style="color: #333; margin-bottom: 10px;">Seller Not Found</h2>
            <p style="color: #666; margin-bottom: 30px;">The seller profile you're looking for doesn't exist or is no longer available.</p>
            <a href="category_page.php?category=all" class="btn btn-primary" style="background: #1a4d2e; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none;">
                <i class="fas fa-shopping-bag"></i> Browse All Products
            </a>
        </div>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>

</body>
</html>