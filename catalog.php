<?php 
// --- SAFE MODE LOGIC ---

// 1. Define Default Titles (Fallbacks)
$t_fresh  = 'TAZE ÜRÜNLER';
$t_dairy  = 'SÜT VE YUMURTA';
$t_bakery = 'FIRIN';
$t_pantry = 'KİLER';
$t_bev    = 'İÇECEKLER';
$t_home   = 'EV VE BAHÇE';

// 2. Try to load translations
// Using @ to suppress errors if file is missing
@include_once 'lang_config.php'; 

// 3. Overwrite defaults ONLY if the translation array is valid
if (isset($text) && is_array($text)) {
    if (!empty($text['cat_fresh']))  $t_fresh  = $text['cat_fresh'];
    if (!empty($text['cat_dairy']))  $t_dairy  = $text['cat_dairy'];
    if (!empty($text['cat_bakery'])) $t_bakery = $text['cat_bakery'];
    if (!empty($text['cat_pantry'])) $t_pantry = $text['cat_pantry'];
    if (!empty($text['cat_bev']))    $t_bev    = $text['cat_bev'];
    if (!empty($text['cat_home']))   $t_home   = $text['cat_home'];
}

// 4. Image Sources (Centralized here to ensure they aren't broken)
$img_fresh  = "https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80";
$img_dairy  = "https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=600&q=80";
$img_bakery = "https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80";
// Pantry uses a color instead of an image
$img_bev    = "https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=600&q=80";
$img_home   = "https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=600&q=80";
?>

<!-- CATALOG GRID -->
<div class="catalog-container" id="catalog-anchor">
    
    <!-- 1. Fresh Produce -->
    <a href="index.php?category=fresh_produce" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_fresh; ?>');"></div>
        <div class="cat-overlay">
            <h3><?php echo $t_fresh; ?></h3>
        </div>
    </a>

    <!-- 2. Dairy & Eggs -->
    <a href="index.php?category=dairy_eggs" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_dairy; ?>');"></div>
        <div class="cat-overlay">
            <h3><?php echo $t_dairy; ?></h3>
        </div>
    </a>

    <!-- 3. Bakery -->
    <a href="index.php?category=bakery" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_bakery; ?>');"></div>
        <div class="cat-overlay">
            <h3><?php echo $t_bakery; ?></h3>
        </div>
    </a>

    <!-- 4. Pantry (Gray Box) -->
    <a href="index.php?category=pantry" class="catalog-item">
        <div class="cat-img" style="background-color: #9ca3af; background-image: none;"></div>
        <div class="cat-overlay">
            <h3><?php echo $t_pantry; ?></h3>
        </div>
    </a>

    <!-- 5. Beverages -->
    <a href="index.php?category=beverages" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_bev; ?>');"></div>
        <div class="cat-overlay">
            <h3><?php echo $t_bev; ?></h3>
        </div>
    </a>

    <!-- 6. Home & Garden -->
    <a href="index.php?category=home_garden" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_home; ?>');"></div>
        <div class="cat-overlay">
            <h3><?php echo $t_home; ?></h3>
        </div>
    </a>

</div>

<style>
/* ROBUST STYLES */
.catalog-container {
    max-width: 1200px;
    margin: 60px auto; /* Increased top margin to prevent overlap */
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 25px;
    position: relative;
    z-index: 5;
    min-height: 500px; /* FORCE HEIGHT: Container cannot collapse to 0 */
}

.catalog-item {
    position: relative;
    height: 250px; /* Fixed height for items */
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
    background: #333; /* Dark fallback if image fails */
    display: block; /* Ensure it behaves as a block */
}

.catalog-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.cat-img {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    transition: transform 0.6s ease;
}

.catalog-item:hover .cat-img {
    transform: scale(1.1);
}

.cat-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.cat-overlay h3 {
    color: white;
    font-size: 26px;
    font-weight: 800;
    text-transform: uppercase;
    font-family: 'Arial', sans-serif;
    letter-spacing: 1px;
    text-shadow: 0 2px 5px rgba(0,0,0,0.7);
    margin: 0;
    text-align: center;
    width: 100%;
}

@media (max-width: 768px) {
    .catalog-container {
        grid-template-columns: 1fr;
    }
}
</style>