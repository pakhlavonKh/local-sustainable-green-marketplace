<?php
// Load the library
require_once __DIR__ . '/vendor/autoload.php'; 

function getDBConnection() {
    // UPDATED CREDENTIALS
    $username = "admin";       // The new simple username you created
    $password = "password123q"; // The new simple password you created
    
    $encoded_pwd = urlencode($password);
    
    // Connection String
    $uri = "mongodb+srv://{$username}:{$encoded_pwd}@local-sustainable-green.ygi7dra.mongodb.net/?appName=Local-sustainable-green-marketplace";

    try {
        $client = new MongoDB\Client($uri);
        return $client->leaf_market;
    } catch (Exception $e) {
        return null;
    }
}
?>