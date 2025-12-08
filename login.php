<?php
session_start();
require_once 'db_connect.php';

$error = '';
$redirect_to = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : 'index.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $db = getDBConnection();
        if ($db) {
            try {
                $usersCollection = $db->users;
                $user = $usersCollection->findOne(['email' => $email]);
            } catch (Exception $e) {
                error_log('MongoDB error in login.php: ' . $e->getMessage());
                $error = "Database connection failed. Please try again later.";
                $user = null;
            }

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = (string)$user['_id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                // SMART REDIRECT LOGIC
                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } elseif ($user['role'] === 'seller') {
                    header("Location: seller_dashboard.php");
                } else {
                    header("Location: " . $redirect_to);
                }
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Database connection failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Leaf Leaf Green Market</title>
    <link rel="stylesheet" href="style.css?v=25"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #fff; color: #333; }
        .auth-wrapper { max-width: 1200px; margin: 60px auto; padding: 0 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 80px; }
        .auth-title { font-family: 'Playfair Display', serif; font-size: 38px; font-weight: 400; margin-bottom: 20px; color: #000; }
        .auth-subtitle { font-size: 16px; color: #666; margin-bottom: 30px; font-weight: 300; }
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 14px; color: #333; }
        .required-star { color: #e53e3e; }
        .form-input { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 15px; color: #333; box-sizing: border-box; transition: border-color 0.2s; }
        .form-input:focus { outline: none; border-color: #1a4d2e; }
        .btn-black { background-color: #000; color: #fff; border: none; padding: 15px 40px; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; width: 100%; border-radius: 2px; transition: background 0.3s; }
        .btn-black:hover { background-color: #333; }
        .benefits-list { list-style: none; padding: 0; margin: 0; }
        .benefits-list li { margin-bottom: 15px; display: flex; align-items: flex-start; font-size: 15px; color: #555; line-height: 1.5; }
        .benefits-list i { color: #1a4d2e; margin-right: 12px; margin-top: 4px; }
        .error-box { background: #fff5f5; color: #c53030; padding: 12px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #c53030; }
        
        .seller-promo { margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
        .seller-link { color: #1a4d2e; font-weight: bold; text-decoration: underline; cursor: pointer; }
        
        @media (max-width: 768px) { .auth-wrapper { grid-template-columns: 1fr; gap: 40px; } }
    </style>
</head>
<body class="login-page">

    <?php include 'navbar.php'; ?>

    <div class="auth-wrapper">
        
        <!-- Login Form -->
        <div class="login-column">
            <h1 class="auth-title">Login</h1>
            <p class="auth-subtitle">Please enter your e-mail and password:</p>

            <?php if ($error): ?>
                <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect_to); ?>">

                <div class="form-group">
                    <label class="form-label">E-mail <span class="required-star">*</span></label>
                    <input type="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span class="required-star">*</span></label>
                    <input type="password" name="password" class="form-input" required>
                </div>

                <button type="submit" class="btn-black">LOG IN</button>
            </form>
        </div>

        <!-- New Customer & Seller Info -->
        <div class="info-column">
            <h1 class="auth-title">New Customer?</h1>
            <p class="auth-subtitle">Create an account with us:</p>
            <ul class="benefits-list">
                <li><i class="fas fa-check"></i> Check out faster.</li>
                <li><i class="fas fa-check"></i> Track orders.</li>
                <li><i class="fas fa-check"></i> Save favorites.</li>
            </ul>
            <a href="register.php" class="btn-black" style="display:inline-block; text-align:center; text-decoration:none; margin-top:20px;">CREATE ACCOUNT</a>

            <!-- SELLER OPTION ADDED HERE -->
            <div class="seller-promo">
                <p class="auth-subtitle" style="margin-bottom: 10px;">Want to sell your products?</p>
                <a href="register.php?role=seller" class="seller-link">Join as a Seller &rarr;</a>
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>