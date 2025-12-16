<?php
/**
 * Test Script - Verify Seller Products Display
 * 
 * This script helps diagnose issues with seller product visibility
 * by checking the database for products and their seller_id mappings.
 * 
 * Usage: Access this file directly in your browser (e.g., /test_seller_products.php)
 */

session_start();
require_once 'db_connect.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Seller Products Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #1a4d2e; }
        h2 { color: #333; border-bottom: 2px solid #1a4d2e; padding-bottom: 10px; margin-top: 30px; }
        .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #004085; background: #cce5ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1a4d2e; color: white; }
        tr:hover { background: #f5f5f5; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>🧪 Seller Products Diagnostic Tool</h1>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div class='error'>⚠️ You are not logged in. Please log in to test seller functionality.</div>";
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
    echo "</div></body></html>";
    exit();
}

echo "<div class='info'>";
echo "<strong>Current User:</strong> " . htmlspecialchars($_SESSION['username']) . "<br>";
echo "<strong>User ID:</strong> " . htmlspecialchars($_SESSION['user_id']) . "<br>";
echo "<strong>Role:</strong> " . htmlspecialchars($_SESSION['role'] ?? 'Not set') . "<br>";
echo "</div>";

// Get database connection
$db = getDBConnection();

if (!$db) {
    echo "<div class='error'>❌ Unable to connect to MongoDB database.</div>";
    echo "<p>Check your database configuration in <code>db_connect.php</code>.</p>";
    echo "</div></body></html>";
    exit();
}

echo "<div class='success'>✅ Successfully connected to MongoDB database.</div>";

try {
    $productsCollection = $db->products;
    
    // Count total products
    $totalProducts = $productsCollection->countDocuments([]);
    echo "<h2>📦 Database Overview</h2>";
    echo "<p><strong>Total products in database:</strong> $totalProducts</p>";
    
    // Get all products with seller_id
    echo "<h2>🔍 All Products with Seller Information</h2>";
    $allProducts = $productsCollection->find([], ['sort' => ['id' => 1]])->toArray();
    
    if (empty($allProducts)) {
        echo "<div class='error'>No products found in the database.</div>";
    } else {
        echo "<table>";
        echo "<tr><th>ID</th><th>Image</th><th>Title</th><th>Seller Name</th><th>Seller ID</th><th>Price</th></tr>";
        foreach ($allProducts as $product) {
            $sellerId = isset($product['seller_id']) ? htmlspecialchars($product['seller_id']) : '<em style="color:red;">NOT SET</em>';
            $sellerName = isset($product['seller_name']) ? htmlspecialchars($product['seller_name']) : '<em style="color:red;">NOT SET</em>';
            echo "<tr>";
            echo "<td>" . htmlspecialchars($product['id']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($product['image']) . "' class='product-img' alt='Product'></td>";
            echo "<td>" . htmlspecialchars($product['title']) . "</td>";
            echo "<td>$sellerName</td>";
            echo "<td>$sellerId</td>";
            echo "<td>" . number_format($product['price'], 2) . " TL</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check products for current user
    echo "<h2>👤 Products for Current User</h2>";
    $userId = (string)$_SESSION['user_id'];
    echo "<p>Searching for products with <code>seller_id = '$userId'</code></p>";
    
    $myProducts = $productsCollection->find(['seller_id' => $userId])->toArray();
    
    if (empty($myProducts)) {
        echo "<div class='error'>❌ No products found for your user ID.</div>";
        echo "<p><strong>Possible reasons:</strong></p>";
        echo "<ul>";
        echo "<li>You haven't added any products yet</li>";
        echo "<li>Your products have a different seller_id value</li>";
        echo "<li>There's a data type mismatch (string vs integer)</li>";
        echo "</ul>";
        echo "<p><strong>Action:</strong> Try adding a product through the seller dashboard.</p>";
    } else {
        echo "<div class='success'>✅ Found " . count($myProducts) . " product(s) for your user ID.</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Image</th><th>Title</th><th>Price</th><th>Stock</th></tr>";
        foreach ($myProducts as $product) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($product['id']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($product['image']) . "' class='product-img' alt='Product'></td>";
            echo "<td>" . htmlspecialchars($product['title']) . "</td>";
            echo "<td>" . number_format($product['price'], 2) . " TL</td>";
            echo "<td>" . htmlspecialchars($product['stock']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test seller_id data types
    echo "<h2>🔧 Seller ID Data Type Analysis</h2>";
    echo "<p>Checking data types of seller_id values in the database...</p>";
    $sampleProducts = $productsCollection->find([], ['limit' => 5])->toArray();
    echo "<table>";
    echo "<tr><th>Product ID</th><th>Seller ID Value</th><th>Data Type</th></tr>";
    foreach ($sampleProducts as $product) {
        if (isset($product['seller_id'])) {
            $type = gettype($product['seller_id']);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($product['id']) . "</td>";
            echo "<td>" . htmlspecialchars($product['seller_id']) . "</td>";
            echo "<td><code>$type</code></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "<p><em>Note: seller_id should be stored as a <strong>string</strong> type for consistency.</em></p>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<h2>✅ Testing Complete</h2>";
echo "<p><a href='seller_dashboard.php'>Go to Seller Dashboard</a> | <a href='index.php'>Go to Homepage</a></p>";

echo "</div></body></html>";
?>
