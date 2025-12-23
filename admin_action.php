<?php
session_start();
require_once 'db_connect.php';

// Security: Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

$db = getDBConnection();

if ($db && isset($_REQUEST['action'])) {
    try {
        $collection = $db->products;

        // 1. ADD PRODUCT
        if ($_REQUEST['action'] == 'add_product') {
            // Generate a new ID (Find max ID + 1)
            $lastItem = $collection->findOne([], ['sort' => ['id' => -1]]);
            $newId = $lastItem ? ($lastItem['id'] + 1) : 1;

            // Get seller ID from seller_name if exists
            $sellerName = $_POST['seller_name'];
            $seller = $db->users->findOne(['name' => $sellerName, 'role' => 'seller']);
            $sellerId = $seller ? ($seller['seller_id'] ?? 100) : 100;
            $location = $seller ? ($seller['location'] ?? 'Istanbul') : 'Istanbul';

            $newProduct = [
                'id' => $newId,
                'title' => $_POST['title'],
                'title_tr' => $_POST['title'], 
                'category' => $_POST['category'],
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock'],
                'image' => $_POST['image'],
                'seller_name' => $sellerName,
                'seller_id' => $sellerId,
                'location' => $location,
                'desc' => $_POST['desc'] ?? 'Newly added product.',
                'delivery_mode' => $_POST['delivery_mode'] ?? 'cargo',
                'packaging_type' => $_POST['packaging_type'] ?? 'standard',
                'co2_saved' => (float)($_POST['co2_saved'] ?? 0.2),
                'distance' => '5 km'
            ];

            $collection->insertOne($newProduct);
            
            // Update seller's product list
            if ($seller) {
                $db->users->updateOne(
                    ['_id' => $seller['_id']],
                    ['$addToSet' => ['products' => $newId]]
                );
            }
            
            header("Location: admin.php");
            exit();
        }

        // 2. DELETE PRODUCT
        if ($_REQUEST['action'] == 'delete' && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $collection->deleteOne(['id' => $id]);
            header("Location: admin.php");
            exit();
        }
    } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
        error_log('MongoDB timeout in admin_action.php: ' . $e->getMessage());
        die("Database connection timeout. Please try again.");
    } catch (Exception $e) {
        error_log('MongoDB error in admin_action.php: ' . $e->getMessage());
        die("Database error occurred. Please try again.");
    }
}
?>