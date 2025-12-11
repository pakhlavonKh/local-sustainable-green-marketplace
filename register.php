<?php
session_start();
require_once 'db_connect.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    // Validate role - ONLY customer or seller allowed from registration
    if (!in_array($role, ['customer', 'seller'])) {
        $role = 'customer';
    }

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $db = getDBConnection();
        if ($db) {
            try {
                $usersCollection = $db->users;
                $existingUser = $usersCollection->findOne(['email' => $email]);

                if ($existingUser) {
                    $error = "An account with this email already exists.";
                } else {
                    $newUser = [
                        'name' => $name,
                        'email' => $email,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role,
                        'wishlist' => [],
                        'address' => '',
                        'city' => '',
                        'phone' => '',
                        'created_at' => new MongoDB\BSON\UTCDateTime()
                    ];

                    $insertResult = $usersCollection->insertOne($newUser);

                    if ($insertResult->getInsertedCount() == 1) {
                        // LOG IN IMMEDIATELY
                        $_SESSION['user_id'] = (string)$insertResult->getInsertedId();
                        $_SESSION['username'] = $name;
                        $_SESSION['role'] = $role;

                        // REDIRECT BASED ON ROLE
                        if ($role === 'seller') {
                            header("Location: seller_dashboard.php");
                        } elseif ($role === 'admin') {
                            header("Location: admin.php");
                        } else {
                            header("Location: index.php");
                        }
                        exit();
                    } else {
                        $error = "Registration failed.";
                    }
                }
            } catch (Exception $e) {
                error_log('MongoDB error in register.php: ' . $e->getMessage());
                $error = "Database connection failed. Please try again later.";
            }
        } else {
            $error = "Database connection failed.";
        }
    }
}
?>
<!-- (HTML remains the same as your design) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account - Leaf Leaf Green Market</title>
    <link rel="stylesheet" href="style.css?v=23"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page-body login-page">

    <?php include 'navbar.php'; ?>

    <div class="auth-wrapper">
        <div class="register-form-column">
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Please fill in your information below:</p>

            <?php if ($error): ?>
                <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required-star">*</span></label>
                    <input type="text" name="name" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">E-mail <span class="required-star">*</span></label>
                    <input type="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span class="required-star">*</span></label>
                    <input type="password" name="password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Type <span class="required-star">*</span></label>
                    <select name="role" class="form-input" required>
                        <option value="customer">Customer</option>
                        <option value="seller">Seller</option>
                    </select>
                </div>

                <button type="submit" class="btn-black">CREATE ACCOUNT</button>
            </form>
            
            <p style="margin-top: 20px; font-size: 0.9rem; color: #666;">
                Already have an account? <a href="login.php" style="text-decoration: underline;">Log in here.</a>
            </p>
        </div>

        <div class="register-info-column">
            <h1 class="auth-title">Why Register?</h1>
            <ul class="benefits-list">
                <li><i class="fas fa-check"></i> Check out faster.</li>
                <li><i class="fas fa-check"></i> Track orders.</li>
                <li><i class="fas fa-check"></i> Save favorites.</li>
            </ul>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>