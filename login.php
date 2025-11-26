<?php
require 'config.php';
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

// Handle Login Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple JSON-based authentication
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
        header('Location: index.php'); // Redirect to home
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
    <link rel="stylesheet" href="style.css?v=4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="minimal-page">

    <?php include 'navbar.php'; ?>

    <div class="login-wrapper">
        <div class="login-container-minimal">
            
            <h1 class="page-title">ACCOUNT</h1>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" id="loginForm" class="hermes-form">
                
                <!-- STEP 1: EMAIL ONLY -->
                <div id="step-1">
                    <p class="instruction-text">Please enter your email below to access or create your account</p>
                    
                    <div class="input-group-minimal">
                        <label for="email">E-mail *</label>
                        <input type="email" name="email" id="email" required placeholder="name@example.com">
                    </div>
                    
                    <button type="button" class="btn-dark-wide" onclick="showPasswordStep()">CONTINUE</button>
                </div>

                <!-- STEP 2: PASSWORD (Hidden initially) -->
                <div id="step-2" style="display: none;">
                    <p class="instruction-text">Welcome back. Please enter your password.</p>
                    
                    <div class="input-group-minimal">
                        <label for="password">Password *</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-dark-wide">LOGIN</button>
                        <button type="button" class="btn-link" onclick="goBack()">Back to Email</button>
                    </div>
                </div>

            </form>

            <div class="register-prompt">
                <p>Don't have an account yet? <a href="register.php">Create one here.</a></p>
            </div>

        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Simple Logic to switch between Email and Password view
        function showPasswordStep() {
            const emailInput = document.getElementById('email');
            if(emailInput.checkValidity() && emailInput.value.trim() !== "") {
                document.getElementById('step-1').style.display = 'none';
                document.getElementById('step-2').style.display = 'block';
                // Focus on password field
                setTimeout(() => document.getElementById('password').focus(), 100);
            } else {
                // Trigger browser validation message
                emailInput.reportValidity();
            }
        }

        function goBack() {
            document.getElementById('step-2').style.display = 'none';
            document.getElementById('step-1').style.display = 'block';
        }
    </script>
</body>
</html>