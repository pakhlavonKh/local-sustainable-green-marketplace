<?php require 'functions.php'; if (!is_logged_in()) redirect('login.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = 'uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = $targetDir . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }
    $stmt = $pdo->prepare("INSERT INTO products (user_id,title,description,price,image) VALUES (?,?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $title, $desc, $price, $image]);
    redirect('index.php?msg=Ürün+eklendi');
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekle</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container" style="max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    <h2 style="color:var(--green-600); margin-bottom:1.5rem;">Sürdürülebilir Ürün Sat</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group"><label>Ürün Adı</label><input type="text" name="title" class="form-control" required></div>
        <div class="form-group"><label>Açıklama</label><textarea name="description" class="form-control" rows="4"></textarea></div>
        <div class="form-group"><label>Fiyat (₺)</label><input type="number" step="0.01" name="price" class="form-control" required></div>
        <div class="form-group"><label>Resim</label><input type="file" name="image" class="form-control" accept="image/*"></div>
        <button class="btn btn-success" style="width:100%; padding:0.75rem; font-size:1.1rem;">Ürün Ekle</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>