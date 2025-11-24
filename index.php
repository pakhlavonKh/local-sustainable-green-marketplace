<?php
require 'config.php';

// Read "tables" from JSON
$products = read_json(PRODUCTS_FILE, []);
$users    = read_json(USERS_FILE, []);

// some shanges 



// Build user_id → user map
$userById = [];
foreach ($users as $u) {
    if (isset($u['id'])) {
        $userById[$u['id']] = $u;
    }
}

// Sort products by created_at (if present), newest first
usort($products, function ($a, $b) {
    $aTime = $a['created_at'] ?? '';
    $bTime = $b['created_at'] ?? '';
    return strcmp($bTime, $aTime); // descending
});
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green market</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-4">
        <div class="hero-header">
            <h1>Local. Sustainable. Green.</h1>
            <p>Buy and sell eco-friendly products from your community</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?php echo h($_GET['msg']); ?></div>
        <?php endif; ?>

        <div class="grid">
            <?php foreach ($products as $p): ?>
                <?php
                    // seller name
                    $sellerName = 'Unknown seller';
                    if (!empty($p['user_id']) && isset($userById[$p['user_id']])) {
                        $sellerName = $userById[$p['user_id']]['name'] ?? 'Unknown seller';
                    }

                    $price = isset($p['price']) ? (float)$p['price'] : 0.0;
                ?>
                <div class="card">
                    <?php if (!empty($p['image'])): ?>
                        <img src="<?php echo h($p['image']); ?>" alt="<?php echo h($p['title'] ?? ''); ?>" class="card-img">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200?text=Resim+Yok" class="card-img" alt="No image">
                    <?php endif; ?>

                    <div class="card-body">
                        <h3 class="card-title"><?php echo h($p['title'] ?? ''); ?></h3>
                        <p class="card-text"><?php echo h($p['description'] ?? ''); ?></p>
                        <div class="price">₺<?php echo number_format($price, 2); ?></div>
                        <div class="seller">Satıcı: <?php echo h($sellerName); ?></div>

                        <?php if (is_logged_in() && !empty($p['user_id']) && $_SESSION['user_id'] == $p['user_id']): ?>
                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-primary">Edit</a>
                            <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
                        <?php endif; ?>

                        <button
                            class="btn btn-success add-to-cart mt-2"
                            data-id="<?php echo $p['id']; ?>"
                            data-title="<?php echo h($p['title'] ?? ''); ?>"
                            data-price="<?php echo $price; ?>">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="cart.js"></script>
</body>
</html>
