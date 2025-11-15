<?php require 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
    try {
        $stmt->execute([$name,$email,$pass]);
        redirect('login.php?msg=Kayıt+başarılı');
    } catch (PDOException $e) {
        $error = $e->getCode() == 23000 ? "Bu e-posta zaten kayıtlı." : "Hata oluştu.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin: 3rem auto; padding: 2rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    <h2 style="text-align:center; color:var(--green-600); margin-bottom:1.5rem;">Hesap Oluştur</h2>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>Ad Soyad</label><input type="text" name="name" class="form-control" required></div>
        <div class="form-group"><label>E-posta</label><input type="email" name="email" class="form-control" required></div>
        <div class="form-group"><label>Şifre</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-success" style="width:100%; padding:0.75rem; font-size:1.1rem;">Kayıt Ol</button>
    </form>
    <p style="text-align:center; margin-top:1rem;">Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p>
</div>
</body>
</html>