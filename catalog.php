<?php
// --- EXISTING DUMMY DATA FOR MAIN LIST ---
if (empty($products)) {
    $products = [
        ['id'=>101, 'category'=>'vegetables', 'title'=>'Organic Carrots', 'price'=>15.00, 'user_id'=>0, 'image'=>'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=80'],
        ['id'=>102, 'category'=>'vegetables', 'title'=>'Fresh Spinach',  'price'=>20.00, 'user_id'=>0, 'image'=>'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=800&q=80'],
        ['id'=>103, 'category'=>'dairy',      'title'=>'Free Range Eggs', 'price'=>45.00, 'user_id'=>0, 'image'=>'https://images.unsplash.com/photo-1516482738381-adb36a36bdc9?auto=format&fit=crop&w=800&q=80'],
        ['id'=>104, 'category'=>'dairy',      'title'=>'Goat Cheese',     'price'=>85.00, 'user_id'=>0, 'image'=>'https://images.unsplash.com/photo-1559561853-08451507cbe7?auto=format&fit=crop&w=800&q=80'],
        ['id'=>105, 'category'=>'fruits',     'title'=>'Red Apples',      'price'=>25.00, 'user_id'=>0, 'image'=>'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=800&q=80'],
        ['id'=>106, 'category'=>'pantry',     'title'=>'Raw Honey',       'price'=>120.00,'user_id'=>0, 'image'=>'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=800&q=80'],
    ];
}

$selectedCategory = $_GET['category'] ?? null;
?>

<!-- === EXISTING CATALOG SECTION === -->
<div id="catalog-anchor" class="container" style="padding: 60px 20px;">

    <!-- SCENARIO A: CHIC CATEGORY ROW (Default) -->
    <?php if (!$selectedCategory): ?>
        
        <div class="section-header">
            <h2 id="browse-categories">Browse by Category</h2>
            <p>Select a department to view local products.</p>
        </div>

        <div class="category-grid">
            <!-- 1. Vegetables -->
            <a href="index.php?category=vegetables#catalog-anchor" class="category-card">
                <img src="https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Veg">
                <div class="category-overlay">
                    <h3>Vegetables</h3>
                    <p>Seasonal & Organic</p>
                </div>
            </a>
            <!-- 2. Dairy -->
            <a href="index.php?category=dairy#catalog-anchor" class="category-card">
                <img src="https://images.unsplash.com/photo-1628088062854-d1870b4553da?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Dairy">
                <div class="category-overlay">
                    <h3>Dairy & Eggs</h3>
                    <p>Ethical Animal Products</p>
                </div>
            </a>
            <!-- 3. Fruits -->
            <a href="index.php?category=fruits#catalog-anchor" class="category-card">
                <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Fruit">
                <div class="category-overlay">
                    <h3>Fruits</h3>
                    <p>Pesticide-Free</p>
                </div>
            </a>
            <!-- 4. Pantry -->
            <a href="index.php?category=pantry#catalog-anchor" class="category-card">
                <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Pantry">
                <div class="category-overlay">
                    <h3>Pantry</h3>
                    <p>Honey, Oils & Grains</p>
                </div>
            </a>
        </div>

    <!-- SCENARIO B: PRODUCT LIST (Filtered) -->
    <?php else: ?>

        <div class="section-header" style="text-align: center; margin-bottom: 30px;">
            <a href="index.php#catalog-anchor" class="btn btn-success" style="background:transparent; border:1px solid #1a4d2e; color:#1a4d2e; text-decoration:none; padding:8px 16px; width:auto; display:inline-block; margin-bottom:15px;">
                &larr; Back to Categories
            </a>
            <h2 style="text-transform: capitalize; color:#1a4d2e; margin-top:10px;">Category: <?php echo htmlspecialchars($selectedCategory); ?></h2>
        </div>

        <div class="catalog-grid">
            <?php 
            $found = false;
            foreach ($products as $p): 
                if (isset($p['category']) && $p['category'] !== $selectedCategory && $selectedCategory !== 'all') continue;
                
                $found = true;
                $price = isset($p['price']) ? (float)$p['price'] : 0.0;
                $imgSrc = !empty($p['image']) ? $p['image'] : 'https://via.placeholder.com/300x400?text=No+Image';
            ?>
                <!-- Product Card -->
                <div class="product-card" onclick="window.location.href='product_details.php?id=<?php echo $p['id']; ?>'">
                    <div class="card-image-wrapper">
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($p['title'] ?? ''); ?>">
                        <button class="wishlist-btn" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); border-radius: 50%; width: 30px; height: 30px; border: none; cursor: pointer;" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="card-info">
                        <h3 class="card-title"><?php echo htmlspecialchars($p['title'] ?? 'Product'); ?></h3>
                        <div class="card-price">₺<?php echo number_format($price, 2); ?></div>
                        <div class="card-actions">
                            <button 
                                class="btn btn-success" 
                                onclick="event.stopPropagation();"
                                data-id="<?php echo $p['id']; ?>" 
                                data-title="<?php echo htmlspecialchars($p['title'] ?? ''); ?>" 
                                data-price="<?php echo $price; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$found): ?>
            <div style="text-align: center; padding: 50px; background-color: #f9fafb; border-radius: 12px; margin-top: 20px;">
                <h3 style="color: #4b5563;">No products found here yet.</h3>
                <p style="color: #6b7280;">Be the first neighbor to sell <?php echo htmlspecialchars($selectedCategory); ?>!</p>
                <a href="add_product.php" class="btn btn-success" style="margin-top: 15px; display:inline-block; width:auto;">Start Selling</a>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>