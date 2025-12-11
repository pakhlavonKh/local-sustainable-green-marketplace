<?php
require_once 'db_connect.php';

$db = getDBConnection();

if ($db) {
    $usersCollection = $db->users;
    
    // Check if admin exists
    $adminExists = $usersCollection->findOne(['role' => 'admin']);
    
    if ($adminExists) {
        echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 500px; margin: 50px auto;'>";
        echo "<h2>Admin Already Exists</h2>";
        echo "<p>An admin account already exists in the database.</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($adminExists['email']) . "</p>";
        echo "<a href='login.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#991b1b; color:white; text-decoration:none; border-radius:5px;'>Go to Login</a>";
        echo "</div>";
    } else {
        // Create admin
        $adminUser = [
            'name' => 'Admin User',
            'email' => 'admin@leafmarket.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'wishlist' => [],
            'address' => '',
            'city' => '',
            'phone' => '',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
        
        $usersCollection->insertOne($adminUser);
        
        echo "<div style='font-family: sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; max-width: 500px; margin: 50px auto;'>";
        echo "<h2>✓ Admin Account Created!</h2>";
        echo "<p><strong>Email:</strong> admin@leafmarket.com</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p style='color: #dc2626; margin-top: 15px;'><strong>⚠️ IMPORTANT:</strong> Change this password after first login!</p>";
        echo "<a href='login.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>Go to Login</a>";
        echo "</div>";
    }
} else {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 500px; margin: 50px auto;'>";
    echo "<h2>Database Error</h2>";
    echo "<p>Could not connect to MongoDB.</p>";
    echo "</div>";
}
?>
