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
$img_pantry = "https://images.unsplash.com/photo-1542838132-92c53300491e?w=600";
$img_bev    = "https://images.unsplash.com/photo-1544145945-f90425340c7e?w=600";
$img_home   = "https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600";
?>

<!-- CATEGORY GRID SECTION -->
<div class="catalog-container" id="catalog-anchor">
    
    <!-- 1. Fresh Produce -->
    <a href="category_page.php?category=fresh_produce" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_fresh; ?>');"></div>
        <div class="cat-overlay"><h3><?php echo $t_fresh; ?></h3></div>
    </a>

    <!-- 2. Dairy & Eggs -->
    <a href="category_page.php?category=dairy_eggs" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_dairy; ?>');"></div>
        <div class="cat-overlay"><h3><?php echo $t_dairy; ?></h3></div>
    </a>

    <!-- 3. Bakery -->
    <a href="category_page.php?category=bakery" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_bakery; ?>');"></div>
        <div class="cat-overlay"><h3><?php echo $t_bakery; ?></h3></div>
    </a>

    <!-- 4. Pantry -->
    <a href="category_page.php?category=pantry" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_pantry; ?>');"></div>
        <div class="cat-overlay"><h3><?php echo $t_pantry; ?></h3></div>
    </a>

    <!-- 5. Beverages -->
    <a href="category_page.php?category=beverages" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_bev; ?>');"></div>
        <div class="cat-overlay"><h3><?php echo $t_bev; ?></h3></div>
    </a>

    <!-- 6. Home & Garden -->
    <a href="category_page.php?category=home_garden" class="catalog-item">
        <div class="cat-img" style="background-image: url('<?php echo $img_home; ?>');"></div>
        <div class="cat-overlay"><h3><?php echo $t_home; ?></h3></div>
    </a>

</div>
