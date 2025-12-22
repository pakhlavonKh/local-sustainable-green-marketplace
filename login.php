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
                
                // Sync session wishlist to database
                if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist']) && $db) {
                    try {
                        $usersCollection = $db->users;
                        // Merge session wishlist with database wishlist
                        $sessionWishlist = $_SESSION['wishlist'];
                        $usersCollection->updateOne(
                            ['_id' => $user['_id']],
                            ['$addToSet' => ['wishlist' => ['$each' => $sessionWishlist]]]
                        );
                    } catch (Exception $e) {
                        error_log('Wishlist sync error: ' . $e->getMessage());
                    }
                }
                
                // Load user's wishlist to session
                if (isset($user['wishlist'])) {
                    $_SESSION['wishlist'] = is_array($user['wishlist']) ? $user['wishlist'] : [];
                }
                
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Leaf Leaf Green Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css?v=25"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page-body login-page">

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