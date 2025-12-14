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
    redirect('index.php?msg=Product+updated+successfully');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    <h2 style="color: var(--green-600); margin-bottom: 1.5rem; text-align: center;">Edit Product</h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="title" class="form-control" value="<?php echo h($product['title']); ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?php echo h($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Price (₺)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>

        <div class="form-group">
            <label>Image (Current: <?php echo $product['image'] ? 'Yes' : 'None'; ?>)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <?php if ($product['image']): ?>
                <div style="margin-top: 0.5rem;">
                    <img src="<?php echo h($product['image']); ?>" alt="Current image" style="max-height: 100px; border-radius: var(--radius-md);">
                </div>
            <?php endif; ?>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button class="btn btn-success" style="flex: 1; padding: 0.75rem;">Save Changes</button>
            <a href="index.php" class="btn btn-outline-primary" style="flex: 1; padding: 0.75rem; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>