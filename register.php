<?php
session_start();
// Use the MongoDB connection
require_once 'db_connect.php'; 

$error = '';
$success = '';

// Handle Registration Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $db = getDBConnection();
        
        if ($db) {
            $usersCollection = $db->users;

            // Check if email already exists
            $existingUser = $usersCollection->findOne(['email' => $email]);

            if ($existingUser) {
                $error = "An account with this email already exists.";
            } else {
                // Add new user
                $newUser = [
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT), // Secure hashing
                    'role' => 'customer', // Default role
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ];

                $insertResult = $usersCollection->insertOne($newUser);

                if ($insertResult->getInsertedCount() == 1) {
                    // Log them in immediately
                    $_SESSION['user_id'] = (string)$insertResult->getInsertedId();
                    $_SESSION['user_name'] = $name;
                    $_SESSION['role'] = 'customer'; // Default role

                    $success = "Account created successfully! Redirecting...";
                    header("refresh:2;url=index.php"); // Redirect after 2 seconds
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        } else {
            // Fallback for Safe Mode (No DB)
            $error = "Database connection failed. Cannot register at this time.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Leaf Leaf Green Market</title>
    <!-- Updated to v=17 to force refresh -->
    <link rel="stylesheet" href="style.css?v=17"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page"> <!-- Reusing login-page class for white background -->

    <?php include 'navbar.php'; ?>

    <div class="register-page-container">
        
        <!-- LEFT COLUMN: Register Form -->
        <div class="register-form-column">
            <h1 class="register-page-title">Create Account</h1>
            <p class="register-description">Please fill in your information below:</p>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                
                <!-- Reusing 'input-group-standard' from login for consistency -->
                <div class="input-group-standard">
                    <label for="name">Full Name <span>*</span></label>
                    <input type="text" name="name" id="name" required placeholder="Full Name">
                </div>

                <div class="input-group-standard">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" name="email" id="email" required placeholder="E-mail">
                </div>

                <div class="input-group-standard">
                    <label for="password">Password <span>*</span></label>
                    <input type="password" name="password" id="password" required placeholder="Password">
                </div>

                <button type="submit" class="btn-black-wide">CREATE ACCOUNT</button>

            </form>
            
            <p style="margin-top: 20px; font-size: 0.9rem; color: #666;">
                Already have an account? <a href="login.php" style="text-decoration: underline;">Log in here.</a>
            </p>
        </div>

        <!-- RIGHT COLUMN: Benefits Info -->
        <div class="register-info-column">
            <h2 class="register-info-title">Why Register?</h2>
            <ul class="register-info-list">
                <li><i class="fas fa-check"></i> Check out faster with saved information.</li>
                <li><i class="fas fa-check"></i> Track your current orders and view order history.</li>
                <li><i class="fas fa-check"></i> Save your favorite items to your wishlist.</li>
                <li><i class="fas fa-check"></i> Receive exclusive offers and updates.</li>
            </ul>
        </div>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>