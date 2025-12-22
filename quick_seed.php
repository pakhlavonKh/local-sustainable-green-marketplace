<?php
// QUICK SEED: Creates sellers + products in one go
require_once 'db_connect.php';
require_once 'data_products.php';

$db = getDBConnection();

if (!$db) {
    die("❌ MongoDB connection failed!");
}

try {
    $usersCollection = $db->users;
    $productsCollection = $db->products;

    // STEP 1: Delete ALL existing sellers and products
    $deletedSellers = $usersCollection->deleteMany(['role' => 'seller']);
    $deletedProducts = $productsCollection->deleteMany([]);
    
    echo "🗑️  DELETED:\n";
    echo "   - {$deletedSellers->getDeletedCount()} sellers\n";
    echo "   - {$deletedProducts->getDeletedCount()} products\n\n";

    // STEP 2: Get unique sellers from products
    $sellers = [];
    foreach ($products_db as $product) {
        $sellerName = $product['seller_name'];
        if (!isset($sellers[$sellerName])) {
            $sellers[$sellerName] = [
                'name' => $sellerName,
                'email' => strtolower(str_replace([' ', '\'', '&', '.'], '', $sellerName)) . '@leafmarket.com',
            ];
        }
    }

    echo "Found " . count($sellers) . " unique sellers\n\n";

    // STEP 3: Create seller accounts from scratch
    $sellerIds = [];
    foreach ($sellers as $sellerName => $seller) {
        $result = $usersCollection->insertOne([
            'name' => $sellerName,
            'email' => $seller['email'],
            'password' => password_hash('seller123', PASSWORD_DEFAULT),
            'role' => 'seller',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        $sellerIds[$sellerName] = (string)$result->getInsertedId();
        echo "✓ Created seller: {$sellerName} ({$seller['email']})\n";
    }

    // STEP 4: Insert all products with seller IDs
    echo "\n📦 Inserting products...\n";

    $products = [];
    foreach ($products_db as $product) {
        $sellerName = $product['seller_name'];
        if (isset($sellerIds[$sellerName])) {
            $product['seller_id'] = $sellerIds[$sellerName];
            $products[] = $product;
        }
    }

    $result = $productsCollection->insertMany($products);
    
    echo "✅ SUCCESS!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📦 Inserted: {$result->getInsertedCount()} products\n";
    echo "👥 Sellers: " . count($sellerIds) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🔐 Login credentials:\n";
    echo "   Email: any seller email @leafmarket.com\n";
    echo "   Password: seller123\n\n";
    echo "🌐 Go to: http://localhost/GreenMarket/\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
