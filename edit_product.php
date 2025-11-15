<?php
require 'config.php';
if (!is_logged_in()) redirect('login.php');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$product = $stmt->fetch();

if (!$product) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $price = (float)$_POST['price'];

    $image = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $targetDir = 'uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = $targetDir . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
        // Optional: delete old image
        if ($product['image'] && file_exists($product['image']) && $product['image'] !== $image) {
            @unlink($product['image']);
        }
    }

    $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $desc, $price, $image, $id]);
    redirect('index.php?msg=Ürün+güncellendi');
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    <h2 style="color: var(--green-600); margin-bottom: 1.5rem; text-align: center;">Ürün Düzenle</h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Ürün Adı</label>
            <input type="text" name="title" class="form-control" value="<?php echo h($product['title']); ?>" required>
        </div>

        <div class="form-group">
            <label>Açıklama</label>
            <textarea name="description" class="form-control" rows="4"><?php echo h($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Fiyat (₺)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>

        <div class="form-group">
            <label>Resim (Mevcut: <?php echo $product['image'] ? 'Var' : 'Yok'; ?>)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <?php if ($product['image']): ?>
                <div style="margin-top: 0.5rem;">
                    <img src="<?php echo h($product['image']); ?>" alt="Current" style="max-height: 100px; border-radius: var(--radius-md);">
                </div>
            <?php endif; ?>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button class="btn btn-success" style="flex: 1; padding: 0.75rem;">Kaydet</button>
            <a href="index.php" class="btn btn-outline-primary" style="flex: 1; padding: 0.75rem; text-align: center;">İptal</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>