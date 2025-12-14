<?php
require_once 'db_connect.php';

$db = getDBConnection();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $usersCollection = $db->users;
        
        // Check if email already exists
        $existingUser = $usersCollection->findOne(['email' => $email]);
        
        if ($existingUser) {
            $error = "An account with this email already exists.";
        } else {
            $newAdmin = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'admin',
                'wishlist' => [],
                'address' => '',
                'city' => '',
                'phone' => '',
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ];
            
            $usersCollection->insertOne($newAdmin);
            $message = "Admin account created successfully!";
        }
    }
}

// Get all existing admins
$admins = [];
if ($db) {
    $admins = $db->users->find(['role' => 'admin'], ['sort' => ['created_at' => -1]])->toArray();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-creator {
            max-width: 800px;
            margin: 50px auto;
            font-family: sans-serif;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-container h2 {
            color: #1a4d2e;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #1a4d2e;
        }
        .btn-submit {
            background: #1a4d2e;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }
        .btn-submit:hover {
            background: #14532d;
        }
        .success-msg {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .admin-list {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-list h3 {
            color: #1a4d2e;
            margin-top: 0;
        }
        .admin-item {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-item:last-child {
            border-bottom: none;
        }
        .admin-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="admin-creator">
        <h1 style="color: #1a4d2e;">Add New Admin Account</h1>
        
        <div class="form-container">
            <h2>Create Admin</h2>
            
            <?php if ($message): ?>
                <div class="success-msg"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-submit">Create Admin Account</button>
            </form>
        </div>
        
        <div class="admin-list">
            <h3>Existing Admin Accounts (<?php echo count($admins); ?>)</h3>
            
            <?php if (empty($admins)): ?>
                <p style="color: #666;">No admin accounts found.</p>
            <?php else: ?>
                <?php foreach ($admins as $admin): ?>
                    <?php $admin = (array)$admin; ?>
                    <div class="admin-item">
                        <div>
                            <strong><?php echo htmlspecialchars($admin['name']); ?></strong><br>
                            <span style="color: #666; font-size: 14px;"><?php echo htmlspecialchars($admin['email']); ?></span>
                        </div>
                        <span class="admin-badge">ADMIN</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="admin.php" style="display:inline-block; padding:10px 20px; background:#1a4d2e; color:white; text-decoration:none; border-radius:5px; margin-right: 10px;">Go to Admin Panel</a>
            <a href="index.php" style="display:inline-block; padding:10px 20px; background:#666; color:white; text-decoration:none; border-radius:5px;">Go to Home</a>
        </div>
    </div>
</body>
</html>
