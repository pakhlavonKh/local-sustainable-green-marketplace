<?php
require 'config.php';
require 'functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_POST['product_id'];

$stmt = $pdo->prepare("SELECT id, title, price FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php?msg=Ürün bulunamadı');
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = [
        'id'    => $product['id'],
        'title' => $product['title'],
        'price' => $product['price'],
        'qty'   => 1,
    ];
} else {
    $_SESSION['cart'][$id]['qty']++;
}

header('Location: index.php?msg=Ürün sepete eklendi');
exit;
