<?php
require_once 'db_connect.php';

$db = getDBConnection();

if (!$db) {
    die("Could not connect to database");
}

$usersCollection = $db->users;
$sellers = $usersCollection->find(['role' => 'seller'])->toArray();
$customers = $usersCollection->find(['role' => 'customer'])->toArray();
$admins = $usersCollection->find(['role' => 'admin'])->toArray();

?>
<!DOCTYPE html>
<html>
<head>
    <title>All Accounts - GreenMarket</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        h1 { color: #1a4d2e; margin-bottom: 30px; }
        h2 { color: #2d5a3d; margin-top: 30px; border-bottom: 2px solid #1a4d2e; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #1a4d2e; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f9f9f9; }
        .count { background: #dcfce7; color: #166534; padding: 5px 15px; border-radius: 20px; font-weight: bold; }
        .password { color: #dc2626; font-weight: bold; }
        .copy-btn { background: #1a4d2e; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px; }
        .copy-btn:hover { background: #2d5a3d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍃 GreenMarket - All Accounts</h1>
        
        <!-- SELLERS -->
        <h2>👨‍🌾 Seller Accounts <span class="count"><?php echo count($sellers); ?> accounts</span></h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach ($sellers as $seller): 
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($seller['name'] ?? 'N/A'); ?></td>
                    <td>
                        <code><?php echo htmlspecialchars($seller['email'] ?? 'N/A'); ?></code>
                        <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($seller['email']); ?>')">Copy</button>
                    </td>
                    <td><span class="password">seller123</span></td>
                    <td><small><?php echo htmlspecialchars((string)$seller['_id']); ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- CUSTOMERS -->
        <h2>🛒 Customer Accounts <span class="count"><?php echo count($customers); ?> accounts</span></h2>
        <?php if (count($customers) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach ($customers as $customer): 
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($customer['name'] ?? 'N/A'); ?></td>
                    <td><code><?php echo htmlspecialchars($customer['email'] ?? 'N/A'); ?></code></td>
                    <td><small><?php echo htmlspecialchars((string)$customer['_id']); ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="color: #666; padding: 20px; background: #f9f9f9; border-radius: 5px;">No customer accounts found.</p>
        <?php endif; ?>

        <!-- ADMINS -->
        <h2>🔐 Admin Accounts <span class="count"><?php echo count($admins); ?> accounts</span></h2>
        <?php if (count($admins) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach ($admins as $admin): 
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($admin['name'] ?? 'N/A'); ?></td>
                    <td><code><?php echo htmlspecialchars($admin['email'] ?? 'N/A'); ?></code></td>
                    <td><small><?php echo htmlspecialchars((string)$admin['_id']); ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="color: #666; padding: 20px; background: #f9f9f9; border-radius: 5px;">No admin accounts found.</p>
        <?php endif; ?>

        <!-- Quick Info -->
        <div style="margin-top: 40px; padding: 20px; background: #fef3c7; border-radius: 10px; border-left: 4px solid #f59e0b;">
            <h3 style="margin-top: 0; color: #92400e;">📝 Quick Info</h3>
            <p><strong>All sellers use password:</strong> <span class="password">seller123</span></p>
            <p><strong>Total accounts:</strong> <?php echo count($sellers) + count($customers) + count($admins); ?></p>
            <p><strong>To login as seller:</strong> Use any seller email above with password "seller123"</p>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="index.php" style="display: inline-block; padding: 12px 30px; background: #1a4d2e; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">← Back to Home</a>
            <a href="login.php" style="display: inline-block; padding: 12px 30px; background: #2d5a3d; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-left: 10px;">Go to Login</a>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Email copied to clipboard: ' + text);
            });
        }
    </script>
</body>
</html>
