<?php
require_once 'db_connect.php';

$db = getDBConnection();

if ($db) {
    $usersCollection = $db->users;
    
    // Find all admin accounts
    $admins = $usersCollection->find(['role' => 'admin'])->toArray();
    
    if (empty($admins)) {
        echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 500px; margin: 50px auto;'>";
        echo "<h2>No Admin Found</h2>";
        echo "<p>No admin accounts exist in the database.</p>";
        echo "<a href='create_admin.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#991b1b; color:white; text-decoration:none; border-radius:5px;'>Create Admin</a>";
        echo "</div>";
    } else {
        echo "<div style='font-family: sans-serif; padding: 20px; background: #dbeafe; color: #1e40af; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
        echo "<h2>Admin Account(s) Found</h2>";
        echo "<p>Here are the admin account emails in the database:</p>";
        echo "<ul style='line-height: 2;'>";
        foreach ($admins as $admin) {
            $admin = (array)$admin;
            echo "<li><strong>" . htmlspecialchars($admin['email']) . "</strong> - " . htmlspecialchars($admin['name']) . "</li>";
        }
        echo "</ul>";
        echo "<p style='margin-top: 20px;'><strong>If you forgot the password, you'll need to reset it in MongoDB or delete and recreate the admin account.</strong></p>";
        echo "<a href='login.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1e40af; color:white; text-decoration:none; border-radius:5px;'>Go to Login</a>";
        echo "</div>";
    }
} else {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 500px; margin: 50px auto;'>";
    echo "<h2>Database Error</h2>";
    echo "<p>Could not connect to MongoDB.</p>";
    echo "</div>";
}
?>
