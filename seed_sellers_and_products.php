<?php
// COMPREHENSIVE SEED SCRIPT: Creates seller accounts + products linked to them
// Run this ONCE to populate database with realistic demo data

require_once 'db_connect.php';

$db = getDBConnection();

if (!$db) {
    die("<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 600px; margin: 50px auto;'>
    <h1>Connection Failed</h1>
    <p>Could not connect to MongoDB. Check your drivers or internet connection.</p>
    </div>");
}

try {
    $usersCollection = $db->users;
    $productsCollection = $db->products;

    $sellerEmails = [
        'ahmetsorchard@leafmarket.com' => 'Ahmet\'s Orchard',
        'greenrootsfarm@leafmarket.com' => 'Green Roots Farm',
        'thegoldennestfarm@leafmarket.com' => 'The Golden Nest Farm',
        'uskudargardens@leafmarket.com' => 'Uskudar Gardens',
        'perabakery@leafmarket.com' => 'Pera Bakery',
        'beenatural@leafmarket.com' => 'Bee Natural',
        'milkyway@leafmarket.com' => 'Milky Way',
        'citrusco@leafmarket.com' => 'Citrus & Co',
        'citygreen@leafmarket.com' => 'City Green',
        'sunnygroves@leafmarket.com' => 'Sunny Groves',
        'citygreenhouse@leafmarket.com' => 'City Greenhouse',
        'aegeandairy@leafmarket.com' => 'Aegean Dairy',
        'yogurtmama@leafmarket.com' => 'Yogurt Mama',
        'highlandfarms@leafmarket.com' => 'Highland Farms',
        'unclesimit@leafmarket.com' => 'Uncle Simit',
        'grainco@leafmarket.com' => 'Grain & Co',
        'frenchtouch@leafmarket.com' => 'French Touch',
        'grandmasoven@leafmarket.com' => 'Grandma\'s Oven',
        'olivebranch@leafmarket.com' => 'Olive Branch',
        'sundry@leafmarket.com' => 'Sun Dry',
        'anatoliagrains@leafmarket.com' => 'Anatolia Grains',
        'nuthouse@leafmarket.com' => 'Nut House',
        'fermentlab@leafmarket.com' => 'Ferment Lab',
        'plantmom@leafmarket.com' => 'Plant Mom',
        'puresoap@leafmarket.com' => 'Pure Soap',
        'citrusgarden@leafmarket.com' => 'Citrus Garden',
        'theherbalistco@leafmarket.com' => 'The Herbalist Co.',
        'fruitmaster@leafmarket.com' => 'Fruit Master.',
        'thelivingkitchen@leafmarket.com' => 'The Living Kitchen.',
        'goldenmeadowsdairy@leafmarket.com' => 'Golden Meadows Dairy.',
        'velvetdairyco@leafmarket.com' => 'Velvet Dairy Co.',
    ];

    // Clear existing products only
    $productsCollection->deleteMany([]);

    // Get existing seller IDs from database
    $sellerIds = [];
    foreach ($sellerEmails as $email => $name) {
        $seller = $usersCollection->findOne(['email' => $email]);
        if ($seller) {
            $sellerIds[$name] = (string)$seller['_id'];
        }
    }
    
    // If sellers don't exist, show error
    if (count($sellerIds) < count($sellerEmails)) {
        throw new Exception("Not all seller accounts found. Please ensure all sellers are registered first. Found: " . count($sellerIds) . "/" . count($sellerEmails));
    }

    // STEP 2: Load products from data_products.php and update with seller IDs
    require_once 'data_products.php';
    
    $products = [];
    foreach ($products_db as $product) {
        // Update seller_id with real MongoDB ID
        $sellerName = $product['seller_name'];
        if (isset($sellerIds[$sellerName])) {
            $product['seller_id'] = $sellerIds[$sellerName];
            $products[] = $product;
        }
    }

    // Insert all products
    $insertResult = $productsCollection->insertMany($products);

    echo "<div style='font-family: sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>✅ Products Linked Successfully!</h1>";
    echo "<p>Linked <strong>" . $insertResult->getInsertedCount() . "</strong> products to your existing " . count($sellerIds) . " seller accounts.</p>";
    echo "<p><strong>Note:</strong> Products now use local images from <code>/images/</code> folder. Make sure to add your product images there.</p>";
    echo "<h3>Your Seller Credentials:</h3>";
    echo "<ul style='text-align: left;'>";
    echo "<li><strong>Email:</strong> Any @leafmarket.com email (e.g., ahmetsorchard@leafmarket.com)</li>";
    echo "<li><strong>Password:</strong> seller123</li>";
    echo "</ul>";
    echo "<p style='margin-top: 20px;'>Now sellers can log in and see their products!</p>";
    echo "<a href='index.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>Go to Home</a> ";
    echo "<a href='login.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1a4d2e; color:white; text-decoration:none; border-radius:5px;'>Login as Seller</a>";
    echo "</div>";

} catch (Exception $e) {
    error_log('Seeding error: ' . $e->getMessage());
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>Error During Seeding</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>
