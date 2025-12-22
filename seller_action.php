<?php
session_start();
require_once 'db_connect.php';

// 1. SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$db = getDBConnection();

if ($db) {
    try {
        $productsCollection = $db->products;

        // ADD PRODUCT
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
            $last = $productsCollection->findOne([], ['sort' => ['id' => -1]]);
            $newId = $last ? ($last['id'] + 1) : 1;
            $imgId = trim($_POST['image_id']);
            if(empty($imgId)) $imgId = '1560806887-1e4cd0b6cbd6';
            $imgUrl = "https://images.unsplash.com/photo-" . $imgId . "?auto=format&fit=crop&w=500&q=80";

            $newProduct = [
                'id' => $newId,
                'title' => $_POST['title'],
                'title_tr' => $_POST['title'],
                'category' => $_POST['category'],
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock'],
                'image' => $imgUrl,
                'seller_name' => $_SESSION['username'],
                'seller_id' => (string)$_SESSION['user_id'], // Linked to seller
                'desc' => 'Freshly listed by ' . $_SESSION['username'],
                'delivery_mode' => 'bike', 'packaging_type' => 'plastic_free', 'co2_saved' => 0.5, 'distance' => '2 km', 'reviews' => []
            ];
            $productsCollection->insertOne($newProduct);
        }

        // UPDATE PRODUCT
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
            $updateData = [
                'title' => $_POST['title'],
                'title_tr' => $_POST['title'],
                'category' => $_POST['category'],
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock']
            ];
            
            // Only update image if provided
            if (!empty($_POST['image_id'])) {
                $imgId = trim($_POST['image_id']);
                $updateData['image'] = "https://images.unsplash.com/photo-" . $imgId . "?auto=format&fit=crop&w=500&q=80";
            }
            
            $productsCollection->updateOne(
                [
                    'id' => (int)$_POST['product_id'],
                    'seller_id' => (string)$_SESSION['user_id']
                ],
                ['$set' => $updateData]
            );
        }

        // DELETE PRODUCT
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $productsCollection->deleteOne([
                'id' => (int)$_GET['id'], 
                'seller_id' => (string)$_SESSION['user_id']
            ]);
        }
    } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
        error_log('MongoDB timeout in seller_action.php: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('MongoDB error in seller_action.php: ' . $e->getMessage());
    }
}

// Handle redirects after actions
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : (isset($_GET['redirect']) ? $_GET['redirect'] : 'seller_dashboard.php');
header("Location: " . $redirect);
exit();
?>