<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. ADD ITEM (From Product Page)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    
    $id = $_POST['product_id'];
    
    // SECURITY CHECK: Are you logged in?
    if (!isset($_SESSION['user_id'])) {
        $current_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php";
        header("Location: login.php?redirect=" . urlencode($current_page));
        exit();
    }

    $title = $_POST['title'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $co2 = isset($_POST['co2']) ? $_POST['co2'] : 0;
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
    $available_stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 999;

    // Check current quantity in cart
    $current_cart_qty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] : 0;
    $new_total_qty = $current_cart_qty + $quantity;

    // Stock validation
    if ($new_total_qty > $available_stock) {
        $_SESSION['cart_notice'] = [
            'type' => 'error',
            'message' => sprintf('Cannot add %s. Only %d available in stock (you already have %d in cart)', strip_tags($title), $available_stock, $current_cart_qty)
        ];
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: index.php");
        }
        exit();
    }

    // Check if item already exists in cart
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'title' => $title,
            'price' => $price,
            'image' => $image,
            'co2_saved' => $co2,
            'quantity' => $quantity
        ];
    }

    $_SESSION['cart_notice'] = [
        'type' => 'added',
        'message' => sprintf('%s added to your basket', strip_tags($title))
    ];
    
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
    exit();
}

// 2. REMOVE ITEM (From Cart Page)
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        $removed_title = strip_tags($_SESSION['cart'][$id]['title'] ?? 'Item');
        $_SESSION['cart_notice'] = [
            'type' => 'removed',
            'message' => sprintf('%s removed from your basket', $removed_title)
        ];
    }
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// 3. INCREASE QUANTITY
if (isset($_GET['action']) && $_GET['action'] == 'increase' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        // Check stock from database
        require_once 'db_connect.php';
        $db = getDBConnection();
        if ($db) {
            try {
                $productsCollection = $db->products;
                $product = $productsCollection->findOne(['id' => (int)$id]);
                $available_stock = $product['stock'] ?? 999;
                
                if ($_SESSION['cart'][$id]['quantity'] < $available_stock) {
                    $_SESSION['cart'][$id]['quantity']++;
                } else {
                    $_SESSION['cart_notice'] = [
                        'type' => 'error',
                        'message' => sprintf('Cannot add more. Only %d available in stock', $available_stock)
                    ];
                }
            } catch (Exception $e) {
                // If DB fails, allow increase (fallback)
                $_SESSION['cart'][$id]['quantity']++;
            }
        } else {
            $_SESSION['cart'][$id]['quantity']++;
        }
    }
    header("Location: cart.php");
    exit();
}

// 4. DECREASE QUANTITY
if (isset($_GET['action']) && $_GET['action'] == 'decrease' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        if ($_SESSION['cart'][$id]['quantity'] > 1) {
            $_SESSION['cart'][$id]['quantity']--;
        } else {
            $removed_title = strip_tags($_SESSION['cart'][$id]['title'] ?? 'Item');
            unset($_SESSION['cart'][$id]);
            $_SESSION['cart_notice'] = [
                'type' => 'removed',
                'message' => sprintf('%s removed from your basket', $removed_title)
            ];
        }
    }
    header("Location: cart.php");
    exit();
}

// 5. CLEAR ENTIRE CART
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    unset($_SESSION['cart']);
    $_SESSION['cart_notice'] = [
        'type' => 'removed',
        'message' => 'Basket cleared'
    ];
    header("Location: cart.php");
    exit();
}

header("Location: index.php");
exit();
?>
