<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. ADD ITEM (From Product Page)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    
    // SECURITY CHECK: Are you logged in?
    if (!isset($_SESSION['user_id'])) {
        // Not logged in? Go to login page, then come back to this product
        $prod_id = $_POST['product_id'];
        header("Location: login.php?redirect=product_detail.php?id=" . $prod_id);
        exit();
    }

    $id = $_POST['product_id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $co2 = isset($_POST['co2']) ? $_POST['co2'] : 0;

    // Check if item already exists in cart
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'title' => $title,
            'price' => $price,
            'image' => $image,
            'co2_saved' => $co2,
            'quantity' => 1
        ];
    }
    header("Location: cart.php"); // Go to cart after adding
    exit();
}

// ... (Rest of the file remains the same for remove/increase/decrease) ...
// 2. REMOVE ITEM
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id = $_GET['id'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// 3. INCREASE QUANTITY
if (isset($_GET['action']) && $_GET['action'] == 'increase' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
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
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// 5. CLEAR ENTIRE CART
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

header("Location: index.php");
exit();
?>