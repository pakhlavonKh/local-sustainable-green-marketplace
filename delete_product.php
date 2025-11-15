<?php
require 'config.php';
if (!is_logged_in()) redirect('login.php');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$product = $stmt->fetch();

if ($product) {
    // Delete image if exists
    if ($product['image'] && file_exists($product['image'])) {
        @unlink($product['image']);
    }
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

redirect('index.php?msg=Ürün+silindi');
?>