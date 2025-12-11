<?php
/**
 * Seed Seller Accounts
 * This script creates seller accounts linked to the products in data_products.php
 * Run this ONCE after seeding products to create matching seller accounts
 */

require_once 'db_connect.php';
require_once 'data_products.php';

$db = getDBConnection();

if (!$db) {
    die("Could not connect to MongoDB.");
}

// Extract unique sellers from products
$sellers = [];
foreach ($products_db as $product) {
    $sellerId = $product['seller_id'] ?? null;
    $sellerName = $product['seller_name'] ?? 'Unknown Seller';
    $location = $product['location'] ?? 'Istanbul';
    
    if ($sellerId && !isset($sellers[$sellerId])) {
        $sellers[$sellerId] = [
            'seller_id' => $sellerId,
            'name' => $sellerName,
            'location' => $location,
            'email' => strtolower(str_replace(' ', '', $sellerName)) . '@leafmarket.com',
            'products' => []
        ];
    }
    
    if ($sellerId) {
        $sellers[$sellerId]['products'][] = $product['id'];
    }
}

// Create seller accounts in MongoDB
$usersCollection = $db->users;

// Check if sellers already exist
$existingSellers = $usersCollection->find(['role' => 'seller'])->toArray();
$existingSellerIds = [];
foreach ($existingSellers as $seller) {
    if (isset($seller['seller_id'])) {
        $existingSellerIds[] = $seller['seller_id'];
    }
}

$newSellers = 0;
$skippedSellers = 0;

foreach ($sellers as $seller) {
    // Skip if already exists
    if (in_array($seller['seller_id'], $existingSellerIds)) {
        $skippedSellers++;
        continue;
    }
    
    $newUser = [
        'name' => $seller['name'],
        'email' => $seller['email'],
        'password' => password_hash('seller123', PASSWORD_DEFAULT), // Default password
        'role' => 'seller',
        'seller_id' => $seller['seller_id'],
        'location' => $seller['location'],
        'bio' => 'Local producer committed to sustainable and eco-friendly practices.',
        'rating' => 4.5 + (rand(0, 5) / 10), // Random rating between 4.5-5.0
        'total_sales' => rand(50, 500),
        'products' => $seller['products'],
        'wishlist' => [],
        'address' => $seller['location'],
        'city' => 'Istanbul',
        'phone' => '+90 5' . rand(300000000, 599999999),
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ];
    
    $usersCollection->insertOne($newUser);
    $newSellers++;
}

// Create admin account if it doesn't exist
$adminExists = $usersCollection->findOne(['role' => 'admin']);
if (!$adminExists) {
    $adminUser = [
        'name' => 'Admin User',
        'email' => 'admin@leafmarket.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT), // Change this!
        'role' => 'admin',
        'wishlist' => [],
        'address' => '',
        'city' => '',
        'phone' => '',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ];
    $usersCollection->insertOne($adminUser);
    echo "<div style='font-family: sans-serif; padding: 20px; background: #dbeafe; color: #1e40af; border-radius: 10px; max-width: 600px; margin: 20px auto;'>";
    echo "<h2>Admin Account Created</h2>";
    echo "<p><strong>Email:</strong> admin@leafmarket.com</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<p style='color: #dc2626;'><strong>⚠️ IMPORTANT:</strong> Change the admin password immediately after first login!</p>";
    echo "</div>";
}

// Display results
echo "<div style='font-family: sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
echo "<h1>✓ Seller Accounts Created!</h1>";
echo "<p>Created <strong>" . $newSellers . "</strong> new seller accounts.</p>";
echo "<p>Skipped <strong>" . $skippedSellers . "</strong> existing sellers.</p>";
echo "<p>Total sellers in database: <strong>" . count($sellers) . "</strong></p>";
echo "<hr style='margin: 20px 0; border: 1px solid rgba(22, 101, 52, 0.3);'>";
echo "<h3>Seller Login Credentials:</h3>";
echo "<ul style='line-height: 1.8;'>";
foreach ($sellers as $seller) {
    echo "<li><strong>{$seller['name']}</strong> - {$seller['email']} (Password: seller123)</li>";
}
echo "</ul>";
echo "<p style='margin-top: 20px; font-size: 0.9em; color: #166534;'><strong>Note:</strong> All sellers have default password 'seller123'. They should change it after first login.</p>";
echo "<a href='admin.php' style='display:inline-block; margin-top:20px; padding:12px 24px; background:#166534; color:white; text-decoration:none; border-radius:8px;'>Go to Admin Panel</a>";
echo " ";
echo "<a href='index.php' style='display:inline-block; margin-top:20px; padding:12px 24px; background:#1a4d2e; color:white; text-decoration:none; border-radius:8px;'>Go to Home</a>";
echo "</div>";
?>
