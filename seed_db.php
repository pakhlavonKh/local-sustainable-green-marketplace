<?php
// ⚠️ WARNING: This script uses FAKE seller IDs that don't match real users!
// For seller dashboards to work properly, use: seed_sellers_and_products.php instead
// This script is kept for backward compatibility only.

require_once 'data_products.php'; // Load the 25 products
require_once 'db_connect.php';    // Load the connection

$db = getDBConnection();

if ($db) {
    $collection = $db->products;
    
    // 1. Clear existing data to avoid duplicates
    $collection->deleteMany([]);

    // 2. Insert all products
    // We convert the associative array to a simple list
    $insertResult = $collection->insertMany(array_values($products_db));

    echo "<div style='font-family: sans-serif; padding: 20px; background: #fef3c7; color: #92400e; border-radius: 10px; max-width: 600px; margin: 50px auto; border: 2px solid #fbbf24;'>";
    echo "<h1>⚠️ Products Seeded (with Warning)</h1>";
    echo "<p>Inserted <strong>" . $insertResult->getInsertedCount() . "</strong> products into MongoDB.</p>";
    echo "<div style='background: white; padding: 15px; border-radius: 8px; margin: 15px 0;'>";
    echo "<h3 style='color: #dc2626; margin-top: 0;'>Important Notice:</h3>";
    echo "<p><strong>Seller profiles and dashboards will NOT work properly</strong> because these products use fake seller IDs (101, 102, etc.) that don't match real user accounts.</p>";
    echo "<p><strong>To fix this:</strong> Use <code>seed_sellers_and_products.php</code> instead, which creates real seller accounts and properly links products to them.</p>";
    echo "</div>";
    echo "<a href='seed_sellers_and_products.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#dc2626; color:white; text-decoration:none; border-radius:5px; font-weight: bold;'>Use Proper Seed Script</a> ";
    echo "<a href='index.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#92400e; color:white; text-decoration:none; border-radius:5px;'>Go to Home Anyway</a>";
    echo "</div>";
} else {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>Connection Failed</h1>";
    echo "<p>Could not connect to MongoDB. Check your drivers or internet connection.</p>";
    echo "</div>";
}
?>