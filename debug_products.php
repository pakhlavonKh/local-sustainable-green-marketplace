<?php
require_once 'db_connect.php';

$db = getDBConnection();

echo "<div style='font-family: sans-serif; padding: 20px; max-width: 1000px; margin: 20px auto;'>";
echo "<h1>Database Debug - Products</h1>";

if (!$db) {
    echo "<div style='background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 5px;'>";
    echo "<strong>❌ MongoDB Connection Failed</strong>";
    echo "<p>Could not connect to MongoDB. Check your .env file and connection settings.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #dcfce7; color: #166534; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>";
    echo "<strong>✓ MongoDB Connected</strong>";
    echo "</div>";
    
    try {
        $productsCollection = $db->products;
        
        // Count total products
        $totalCount = $productsCollection->countDocuments([]);
        echo "<h2>Total Products: $totalCount</h2>";
        
        if ($totalCount === 0) {
            echo "<div style='background: #fef3c7; color: #92400e; padding: 15px; border-radius: 5px;'>";
            echo "<strong>⚠️ No Products Found</strong>";
            echo "<p>Your products collection is empty. You need to run the seed script:</p>";
            echo "<p><a href='seed_db.php' style='display:inline-block; padding:10px 20px; background:#92400e; color:white; text-decoration:none; border-radius:5px;'>Run Seed Script</a></p>";
            echo "</div>";
        } else {
            // Fetch all products
            $products = $productsCollection->find([], ['sort' => ['id' => 1], 'limit' => 10])->toArray();
            
            echo "<h3>First 10 Products:</h3>";
            echo "<table style='width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";
            echo "<thead>";
            echo "<tr style='background: #1a4d2e; color: white;'>";
            echo "<th style='padding: 10px; text-align: left;'>ID</th>";
            echo "<th style='padding: 10px; text-align: left;'>Title</th>";
            echo "<th style='padding: 10px; text-align: left;'>Category</th>";
            echo "<th style='padding: 10px; text-align: left;'>Price</th>";
            echo "<th style='padding: 10px; text-align: left;'>Stock</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            foreach ($products as $p) {
                $p = (array)$p;
                echo "<tr style='border-bottom: 1px solid #e2e8f0;'>";
                echo "<td style='padding: 10px;'>" . ($p['id'] ?? 'N/A') . "</td>";
                echo "<td style='padding: 10px;'><strong>" . ($p['title'] ?? 'Unknown') . "</strong></td>";
                echo "<td style='padding: 10px;'>" . ($p['category'] ?? 'N/A') . "</td>";
                echo "<td style='padding: 10px;'>" . ($p['price'] ?? '0') . " TL</td>";
                echo "<td style='padding: 10px;'>" . ($p['stock'] ?? '0') . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            
            if ($totalCount > 10) {
                echo "<p style='margin-top: 20px; color: #666;'>Showing 10 of $totalCount products</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 5px;'>";
        echo "<strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
}

echo "<div style='margin-top: 30px;'>";
echo "<a href='admin.php' style='display:inline-block; padding:10px 20px; background:#1a4d2e; color:white; text-decoration:none; border-radius:5px; margin-right: 10px;'>Go to Admin Panel</a>";
echo "<a href='seed_db.php' style='display:inline-block; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>Seed Products</a>";
echo "</div>";

echo "</div>";
?>
