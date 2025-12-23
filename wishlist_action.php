<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }
    
    if ($action === 'add') {
        if (!in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $product_id;
        }
        echo json_encode(['success' => true, 'action' => 'added']);
    } elseif ($action === 'remove') {
        $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$product_id]));
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit();
}

$db = getDBConnection();
if (!$db) {
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }
    
    if ($action === 'add') {
        if (!in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $product_id;
        }
        echo json_encode(['success' => true, 'action' => 'added']);
    } elseif ($action === 'remove') {
        $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$product_id]));
        echo json_encode(['success' => true, 'action' => 'removed']);
    }
    exit();
}

try {
    $usersCollection = $db->users;
    $userId = new MongoDB\BSON\ObjectId($_SESSION['user_id']);

    if ($action === 'add') {
        $result = $usersCollection->updateOne(
            ['_id' => $userId],
            ['$addToSet' => ['wishlist' => $product_id]]
        );
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
        if (!in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $product_id;
        }
        echo json_encode(['success' => true, 'action' => 'added']);
    } elseif ($action === 'remove') {
        $result = $usersCollection->updateOne(
            ['_id' => $userId],
            ['$pull' => ['wishlist' => $product_id]]
        );
        if (isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$product_id]));
        }
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    error_log('Wishlist error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Operation failed']);
}
