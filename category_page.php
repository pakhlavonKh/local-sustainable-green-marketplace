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
    'dairy_eggs' => isset($text['cat_dairy']) ? $text['cat_dairy'] : 'Dairy & Eggs',
    'bakery' => isset($text['cat_bakery']) ? $text['cat_bakery'] : 'Bakery',
    'pantry' => isset($text['cat_pantry']) ? $text['cat_pantry'] : 'Pantry',
    'beverages' => isset($text['cat_bev']) ? $text['cat_bev'] : 'Beverages',
    'home_garden' => isset($text['cat_home']) ? $text['cat_home'] : 'Home & Garden',
    'all' => isset($text['shop_all']) ? $text['shop_all'] : 'All Products'
];

$page_title = isset($cat_titles[$cat_slug]) ? $cat_titles[$cat_slug] : 'Category';

// 3. Filter Products
$filtered_products = [];
if (isset($products_db) && is_array($products_db)) {
    foreach ($products_db as $p) {
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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">
</head>

<body class="category-page-body">
    <?php include 'navbar.php'; ?>

    <div class="simple-header">
        <h1><?php echo $page_title; ?></h1>

        <!-- Search Bar -->
        <form action="search.php" method="GET" class="catalog-search-bar">
            <input type="text" name="q"
                placeholder="<?php echo isset($text['search_place']) ? $text['search_place'] : 'Search products...'; ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <!-- Category Navigation -->
        <div class="category-nav">
            <a href="category_page.php?category=all"
                class="cat-nav-item <?php echo $cat_slug == 'all' ? 'active' : ''; ?>">
                <i class="fas fa-th"></i> All Products
            </a>
            <a href="category_page.php?category=fresh_produce"
                class="cat-nav-item <?php echo $cat_slug == 'fresh_produce' ? 'active' : ''; ?>">
                <i class="fas fa-leaf"></i>
                <?php echo isset($text['cat_fresh']) ? $text['cat_fresh'] : 'Fresh Produce'; ?>
            </a>
            <a href="category_page.php?category=dairy_eggs"
                class="cat-nav-item <?php echo $cat_slug == 'dairy_eggs' ? 'active' : ''; ?>">
                <i class="fas fa-cheese"></i>
                <?php echo isset($text['cat_dairy']) ? $text['cat_dairy'] : 'Dairy & Eggs'; ?>
            </a>
            <a href="category_page.php?category=bakery"
                class="cat-nav-item <?php echo $cat_slug == 'bakery' ? 'active' : ''; ?>">
                <i class="fas fa-bread-slice"></i>
                <?php echo isset($text['cat_bakery']) ? $text['cat_bakery'] : 'Bakery'; ?>
            </a>
            <a href="category_page.php?category=pantry"
                class="cat-nav-item <?php echo $cat_slug == 'pantry' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> <?php echo isset($text['cat_pantry']) ? $text['cat_pantry'] : 'Pantry'; ?>
            </a>
            <a href="category_page.php?category=beverages"
                class="cat-nav-item <?php echo $cat_slug == 'beverages' ? 'active' : ''; ?>">
                <i class="fas fa-coffee"></i> <?php echo isset($text['cat_bev']) ? $text['cat_bev'] : 'Beverages'; ?>
            </a>
            <a href="category_page.php?category=home_garden"
                class="cat-nav-item <?php echo $cat_slug == 'home_garden' ? 'active' : ''; ?>">
                <i class="fas fa-seedling"></i>
                <?php echo isset($text['cat_home']) ? $text['cat_home'] : 'Home & Garden'; ?>
            </a>
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

    <div class="product-grid">
        <?php if (count($filtered_products) > 0): ?>
            <?php foreach ($filtered_products as $p): ?>
                <?php
                $id = $p['id'];
                $display_title = ($_SESSION['lang'] == 'tr' && !empty($p['title_tr'])) ? $p['title_tr'] : $p['title'];
                $price = $p['price'];
                $img = $p['image'];
                // Check ownership
                $is_mine = ($is_seller && isset($p['seller_id']) && (string) $p['seller_id'] === (string) $my_id);
                ?>

                <div class="product-card">
                    <a href="product_detail.php?id=<?php echo $id; ?>" style="text-decoration:none; color:inherit;">
                        <div class="card-image-wrapper">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($display_title); ?>">

                            <!-- Wishlist Heart Icon -->
                            <button class="wishlist-btn" data-product-id="<?php echo $id; ?>"
                                onclick="toggleWishlist(event, <?php echo $id; ?>)">
                                <i class="far fa-heart"></i>
                            </button>

                            <!-- SELLER ONLY: Delete Button -->
                            <?php if ($is_mine): ?>
                                <a href="seller_action.php?action=delete&id=<?php echo $id; ?>&redirect=category_page.php?category=<?php echo $cat_slug; ?>"
                                    class="btn-delete-overlay" onclick="return confirm('Delete this product?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ($p['stock'] < 5): ?>
                                <span class="badge-low-stock">Low Stock</span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <div class="card-info">
                        <h3 class="card-title"><?php echo htmlspecialchars($display_title); ?></h3>
                        <div class="card-meta">by <?php echo $p['seller_name']; ?></div>
                        <div class="card-price"><?php echo number_format($price, 2); ?> TL</div>
                        <form action="cart_action.php" method="POST" style="margin-top: auto;">
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
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-msg">
                <i class="fas fa-leaf"
                    style="font-size: 40px; margin-bottom: 20px; display:block; opacity:0.3; color: #1a4d2e;"></i>
                <p>No products found in this category.</p>
            </div>
        <?php endif; ?>
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
                <input type="hidden" name="category"
                    value="<?php echo ($cat_slug == 'all') ? 'fresh_produce' : $cat_slug; ?>">

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
                    <input type="text" name="image_id" class="form-input" required
                        placeholder="e.g. 1618331835717-801e976710b2">
                    <small style="color:#888;">Try: 1618331835717-801e976710b2</small>
                </div>
                <button type="submit" class="btn-add-product" style="width:100%; justify-content:center;">Publish
                    Now</button>
            </form>
        </div>
    </div>

    <!-- Wishlist Popup -->
    <div id="wishlistPopup" class="wishlist-popup">
        <i class="fas fa-heart"></i>
        <span class="popup-text">Added to wishlist!</span>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function showWishlistPopup(added) {
            const popup = document.getElementById('wishlistPopup');
            const icon = popup.querySelector('i');
            const text = popup.querySelector('.popup-text');

            if (added) {
                popup.classList.remove('removed');
                icon.className = 'fas fa-heart';
                text.textContent = 'Added to wishlist!';
            } else {
                popup.classList.add('removed');
                icon.className = 'far fa-heart';
                text.textContent = 'Removed from wishlist';
            }

            popup.classList.add('show');

            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }

        function toggleWishlist(event, productId) {
            event.preventDefault();
            event.stopPropagation();

            const btn = event.currentTarget;
            const icon = btn.querySelector('i');
            const isAdded = icon.classList.contains('fas');

            fetch('wishlist_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=' + (isAdded ? 'remove' : 'add') + '&product_id=' + productId
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            btn.style.color = '#e53e3e';
                            showWishlistPopup(true);
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            btn.style.color = '#666';
                            showWishlistPopup(false);
                        }
                        // Update navbar count
                        setTimeout(() => location.reload(), 3000);
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            fetch('get_wishlist.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.wishlist) {
                        data.wishlist.forEach(productId => {
                            const btn = document.querySelector('.wishlist-btn[data-product-id="' + productId + '"]');
                            if (btn) {
                                const icon = btn.querySelector('i');
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                btn.style.color = '#e53e3e';
                            }
                        });
                    }
                });
        });
    </script>

</body>

</html>