<?php
session_start();
require_once 'db_connect.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

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
                        'role' => 'customer',
                        'created_at' => new MongoDB\BSON\UTCDateTime()
                    ];

                    $insertResult = $usersCollection->insertOne($newUser);

                    if ($insertResult->getInsertedCount() == 1) {
                        // LOG IN IMMEDIATELY
                        $_SESSION['user_id'] = (string)$insertResult->getInsertedId();
                        $_SESSION['username'] = $name;
                        $_SESSION['role'] = 'customer';

                        // REDIRECT TO HOMEPAGE
                        header("Location: index.php");
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
        @media (max-width: 768px) { .auth-wrapper { grid-template-columns: 1fr; gap: 40px; } }
    </style>
</head>
<body class="login-page">

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