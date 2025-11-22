<?php require 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        redirect('index.php');
    } else {
        $error = "Geçersiz e-posta veya şifre.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin: 3rem auto; padding: 2rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    <h2 style="text-align:center; color:var(--green-600); margin-bottom:1.5rem;">Login</h2>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if (isset($_GET['msg'])): ?><div class="alert alert-success"><?php echo h($_GET['msg']); ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>E-amil</label><input type="email" name="email" class="form-control" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-success" style="width:100%; padding:0.75rem; font-size:1.1rem;">Login</button>
    </form>
    <p style="text-align:center; margin-top:1rem;">Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>