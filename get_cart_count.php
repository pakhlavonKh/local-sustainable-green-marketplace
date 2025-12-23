<?php
session_start();

header('Content-Type: application/json');

$cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$count = count($cart_items);

echo json_encode(['count' => $count]);
