<?php
require_once 'lang_config.php';

// 1. Get Seller ID
$seller_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// 2. Mock Databases (Ideally this comes from SQL)
$sellers_db = [
    101 => ['name' => 'Ahmet\'s Orchard', 'rating' => 4.9, 'eco_level' => 'Gold', 'location' => 'Kadikoy, Istanbul', 'bio' => 'Growing organic fruits since 1998. No pesticides, just love.'],
    102 => ['name' => 'Green Roots Farm', 'rating' => 4.7, 'eco_level' => 'Silver', 'location' => 'Besiktas, Istanbul', 'bio' => 'Urban farming initiative bringing fresh roots to the city.'],
    103 => ['name' => 'Happy Hen Coop', 'rating' => 5.0, 'eco_level' => 'Gold', 'location' => 'Moda, Istanbul', 'bio' => 'Free-range chickens living their best lives.'],
    104 => ['name' => 'Uskudar Gardens', 'rating' => 4.5, 'eco_level' => 'Bronze', 'location' => 'Uskudar, Istanbul', 'bio' => 'Community garden project supported by locals.'],
    105 => ['name' => 'Pera Bakery', 'rating' => 4.8, 'eco_level' => 'Silver', 'location' => 'Beyoglu, Istanbul', 'bio' => 'Traditional sourdough recipes passed down for generations.'],
    106 => ['name' => 'Bee Natural', 'rating' => 4.9, 'eco_level' => 'Gold', 'location' => 'Sariyer, Istanbul', 'bio' => 'Pure honey from the forests of Northern Istanbul.']
];

$products_db = [
    1 => ['id'=>1, 'seller_id'=>101, 'title'=>'Red Apples', 'price'=>25.00, 'image'=>'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=500'],
    2 => ['id'=>2, 'seller_id'=>102, 'title'=>'Organic Carrots', 'price'=>15.00, 'image'=>'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=500'],
    3 => ['id'=>3, 'seller_id'=>103, 'title'=>'Free Range Eggs', 'price'=>45.00, 'image'=>'https://images.unsplash.com/photo-1511690656952-34342d5c2899?w=500'],
    4 => ['id'=>4, 'seller_id'=>104, 'title'=>'Fresh Spinach', 'price'=>20.00, 'image'=>'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=500'],
    5 => ['id'=>5, 'seller_id'=>105, 'title'=>'Sourdough Bread', 'price'=>35.00, 'image'=>'https://images.unsplash.com/photo-1585476263060-b55d7612e69a?w=500'],
    6 => ['id'=>6, 'seller_id'=>106, 'title'=>'Honey Jar', 'price'=>85.00, 'image'=>'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=500']
];

$seller = isset($sellers_db[$seller_id]) ? $sellers_db[$seller_id] : null;
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $seller ? $seller['name'] : 'Seller'; ?> - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="seller-profile-body">
        


<?php include 'navbar.php'; ?>

<?php if ($seller): ?>
    <div class="seller-header">
        <div class="seller-avatar">
            <i class="fas fa-store"></i>
        </div>
        <h1 class="seller-name"><?php echo $seller['name']; ?></h1>
        <div class="seller-meta">
            <i class="fas fa-map-marker-alt"></i> <?php echo $seller['location']; ?> 
            &bull; 
            <i class="fas fa-star" style="color: gold;"></i> <?php echo $seller['rating']; ?>/5.0
        </div>
        
        <div class="badges">
            <span class="badge badge-verified"><i class="fas fa-check-circle"></i> <?php echo $text['verified_seller']; ?></span>
            <span class="badge badge-gold"><i class="fas fa-leaf"></i> <?php echo $seller['eco_level']; ?> Level</span>
        </div>
        
        <p style="max-width: 600px; margin: 20px auto; color: #555;">
            "<?php echo $seller['bio']; ?>"
        </p>
    </div>

    <div class="container">
        <h2 class="section-title"><?php echo $text['other_products']; ?></h2>
        
        <div class="product-grid">
            <?php 
            // Loop through ALL products and show only ones for this seller
            foreach($products_db as $p) {
                if ($p['seller_id'] == $seller_id) {
                    $original_title = $p['title'];
                    $display_title = (isset($text['products']) && isset($text['products'][$original_title])) 
                                     ? $text['products'][$original_title] 
                                     : $original_title;
                    ?>
                    <a href="product_detail.php?id=<?php echo $p['id']; ?>" class="product-card">
                        <div class="p-img" style="background-image: url('<?php echo $p['image']; ?>');"></div>
                        <div class="p-info">
                            <span class="p-title"><?php echo $display_title; ?></span>
                            <span class="p-price"><?php echo number_format($p['price'], 2); ?> TL</span>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
<?php else: ?>
    <div style="text-align:center; padding: 50px;">Seller not found.</div>
<?php endif; ?>

<?php include 'footer.php'; ?>

</body>
</html>