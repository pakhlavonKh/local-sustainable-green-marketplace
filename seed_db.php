<?php
// This script uploads your current array to MongoDB
// Run this ONCE to populate your empty database!

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

    echo "<div style='font-family: sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>Success!</h1>";
    echo "<p>Inserted <strong>" . $insertResult->getInsertedCount() . "</strong> products into MongoDB.</p>";
    echo "<p>Your cloud database is now ready with the new 25 items.</p>";
    echo "<a href='index.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>Go back to Home</a>";
    echo "</div>";
} else {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>Connection Failed</h1>";
    echo "<p>Could not connect to MongoDB. Check your drivers or internet connection.</p>";
    echo "</div>";
}
?>