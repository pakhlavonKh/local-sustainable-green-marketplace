<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

// Handle Login Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $users = file_exists(USERS_FILE) ? json_decode(file_get_contents(USERS_FILE), true) : [];
    
    $foundUser = null;
    foreach ($users as $u) {
        if ($u['email'] === $email && $u['password'] === $password) {
            $foundUser = $u;
            break;
        }
    }

    if ($foundUser) {
        $_SESSION['user_id'] = $foundUser['id'];
        $_SESSION['user_name'] = $foundUser['name'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Leaf Leaf Green Market</title>
    <!-- Updated to v=16 to force refresh -->
    <link rel="stylesheet" href="style.css?v=16"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">

    <?php include 'navbar.php'; ?>

    <div class="login-container-split">
        
        <!-- LEFT COLUMN: Login Form -->
        <div class="login-form-column">
            <h1 class="login-page-title">Log In</h1>
            <p class="login-description">Please enter your e-mail and password:</p>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                
                <div class="input-group-standard">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" name="email" id="email" required placeholder="E-mail">
                </div>

                <div class="input-group-standard">
                    <label for="password">Password <span>*</span></label>
                    <input type="password" name="password" id="password" required placeholder="Password">
                </div>

                <a href="#" class="forgot-password-link">Forgot password?</a>

                <button type="submit" class="btn-black-wide">LOGIN</button>

            </form>
        </div>

        <!-- RIGHT COLUMN: New Customer -->
        <div class="new-customer-column">
            <h2 class="new-customer-title">New Customer</h2>
            <p class="new-customer-text">
                Create an account to check out faster, track your orders, and save your favorite items.
            </p>
            <a href="register.php" class="btn-outline-black">CREATE AN ACCOUNT</a>
        </div>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>