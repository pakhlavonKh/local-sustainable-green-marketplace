<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $db = getDBConnection();
    
    if ($db) {
        // Update the user's role to 'seller'
        $result = $db->users->updateOne(
            ['email' => $email],
            ['$set' => ['role' => 'seller']]
        );
        
        if ($result->getModifiedCount() > 0) {
            echo "<h2 style='color:green;'>Success! $email is now a Seller.</h2>";
            echo "<p>Please <a href='logout.php'>Log Out</a> and Log In again to see your dashboard.</p>";
        } else {
            echo "<h2 style='color:red;'>No changes made.</h2>";
            echo "<p>Either the email is wrong, or this user is *already* a seller.</p>";
        }
    }
}
?>

<form method="POST" style="padding: 50px; font-family: sans-serif; text-align: center;">
    <h3>Upgrade Account to Seller</h3>
    <input type="email" name="email" placeholder="Enter your email (e.g. seller@gmail.com)" required style="padding: 10px; width: 300px;">
    <br><br>
    <button type="submit" style="padding: 10px 20px; background: #1a4d2e; color: white; border: none; cursor: pointer;">Make me a Seller</button>
</form>