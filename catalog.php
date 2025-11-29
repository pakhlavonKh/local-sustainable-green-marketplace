<?php 
// 1. DEFINE DEFAULTS
$t_fresh  = 'TAZE ÜRÜNLER';
$t_dairy  = 'SÜT VE YUMURTA';
$t_bakery = 'FIRIN';
$t_pantry = 'KİLER';
$t_bev    = 'İÇECEKLER';
$t_home   = 'EV VE BAHÇE';

// 2. Try to load translations
@include_once 'lang_config.php'; 

// 3. Overwrite defaults
if (isset($text) && is_array($text)) {
    if (!empty($text['cat_fresh']))  $t_fresh  = $text['cat_fresh'];
    if (!empty($text['cat_dairy']))  $t_dairy  = $text['cat_dairy'];
    if (!empty($text['cat_bakery'])) $t_bakery = $text['cat_bakery'];
    if (!empty($text['cat_pantry'])) $t_pantry = $text['cat_pantry'];
    if (!empty($text['cat_bev']))    $t_bev    = $text['cat_bev'];
    if (!empty($text['cat_home']))   $t_home   = $text['cat_home'];
}

// 4. Image Sources
$img_fresh  = "https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600";
$img_dairy  = "https://images.unsplash.com/photo-1550583724-b2692b85b150?w=600";
$img_bakery = "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600";
$img_bev    = "https://images.unsplash.com/photo-1544145945-f90425340c7e?w=600";
$img_home   = "https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600";
?>

<!-- CATEGORY GRID SECTION -->
<div class="catalog-container" id="catalog-anchor" 
     style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1200px; width: 100%; margin: 50px auto; padding: 0 20px; position: relative; z-index: 50;">
    
    <!-- 1. Fresh Produce -->
    <a href="category_page.php?category=fresh_produce" class="catalog-item" style="position: relative; height: 250px; border-radius: 12px; overflow: hidden; display: block; background: #222; text-decoration: none;">
        <div class="cat-img" style="background-image: url('<?php echo $img_fresh; ?>'); width: 100%; height: 100%; background-size: cover; background-position: center; transition: transform 0.5s;"></div>
        <div class="cat-overlay"><h3><?php echo $t_fresh; ?></h3></div>
    </a>

    <!-- 2. Dairy & Eggs -->
    <a href="category_page.php?category=dairy_eggs" class="catalog-item" style="position: relative; height: 250px; border-radius: 12px; overflow: hidden; display: block; background: #222; text-decoration: none;">
        <div class="cat-img" style="background-image: url('<?php echo $img_dairy; ?>'); width: 100%; height: 100%; background-size: cover; background-position: center; transition: transform 0.5s;"></div>
        <div class="cat-overlay"><h3><?php echo $t_dairy; ?></h3></div>
    </a>

    <!-- 3. Bakery -->
    <a href="category_page.php?category=bakery" class="catalog-item" style="position: relative; height: 250px; border-radius: 12px; overflow: hidden; display: block; background: #222; text-decoration: none;">
        <div class="cat-img" style="background-image: url('<?php echo $img_bakery; ?>'); width: 100%; height: 100%; background-size: cover; background-position: center; transition: transform 0.5s;"></div>
        <div class="cat-overlay"><h3><?php echo $t_bakery; ?></h3></div>
    </a>

    <!-- 4. Pantry (Gray Box) -->
    <a href="category_page.php?category=pantry" class="catalog-item" style="position: relative; height: 250px; border-radius: 12px; overflow: hidden; display: block; background: #9ca3af; text-decoration: none;">
        <div class="cat-img" style="background-image: none; width: 100%; height: 100%;"></div>
        <div class="cat-overlay"><h3><?php echo $t_pantry; ?></h3></div>
    </a>

    <!-- 5. Beverages -->
    <a href="category_page.php?category=beverages" class="catalog-item" style="position: relative; height: 250px; border-radius: 12px; overflow: hidden; display: block; background: #222; text-decoration: none;">
        <div class="cat-img" style="background-image: url('<?php echo $img_bev; ?>'); width: 100%; height: 100%; background-size: cover; background-position: center; transition: transform 0.5s;"></div>
        <div class="cat-overlay"><h3><?php echo $t_bev; ?></h3></div>
    </a>

    <!-- 6. Home & Garden -->
    <a href="category_page.php?category=home_garden" class="catalog-item" style="position: relative; height: 250px; border-radius: 12px; overflow: hidden; display: block; background: #222; text-decoration: none;">
        <div class="cat-img" style="background-image: url('<?php echo $img_home; ?>'); width: 100%; height: 100%; background-size: cover; background-position: center; transition: transform 0.5s;"></div>
        <div class="cat-overlay"><h3><?php echo $t_home; ?></h3></div>
    </a>

</div>

<style>
/* CATALOG STYLES */
.cat-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.catalog-item:hover .cat-overlay {
    background: rgba(0,0,0,0.15);
}

.catalog-item:hover .cat-img {
    transform: scale(1.1);
}

.cat-overlay h3 {
    color: white;
    font-size: 28px;
    text-transform: uppercase;
    font-family: 'Arial', sans-serif;
    letter-spacing: 1.5px;
    font-weight: 800;
    text-shadow: 0 2px 5px rgba(0,0,0,0.6);
    margin: 0;
    text-align: center;
    z-index: 2;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .catalog-container {
        grid-template-columns: 1fr !important;
    }
}
</style>