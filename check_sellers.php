<?php
require_once 'db_connect.php';

$db = getDBConnection();

if ($db) {
    $usersCollection = $db->users;
    $sellers = $usersCollection->find(['role' => 'seller'])->toArray();
    
    echo "<h2>Found " . count($sellers) . " sellers in database:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Name</th><th>Email</th><th>ID</th></tr>";
    foreach ($sellers as $seller) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($seller['name'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($seller['email'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars((string)$seller['_id']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Could not connect to database";
}
?>
