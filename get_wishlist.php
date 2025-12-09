<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

$wishlist = [];

// Try to get from database if logged in
if (isset($_SESSION['user_id'])) {
    $db = getDBConnection();
    if ($db) {
        try {
            $usersCollection = $db->users;
            $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
            
            if ($user && isset($user['wishlist'])) {
                $wishlist = is_array($user['wishlist']) ? $user['wishlist'] : [];
            }
        } catch (Exception $e) {
            error_log('Get wishlist error: ' . $e->getMessage());
        }
    }
}

// Fallback to session wishlist
if (empty($wishlist) && isset($_SESSION['wishlist'])) {
    $wishlist = $_SESSION['wishlist'];
}

echo json_encode(['success' => true, 'wishlist' => $wishlist]);
