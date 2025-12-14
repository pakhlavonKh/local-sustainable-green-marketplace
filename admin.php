<?php
session_start();
require_once 'db_connect.php';

// 1. SECURITY CHECK: Must be logged in AND be an admin
// If session is missing OR role is not admin, kick them out immediately.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login, not index, to prompt sign-in
    exit();
}

$db = getDBConnection();
$products = [];
$orders = [];
$total_sales = 0;
$total_co2 = 0;
$total_users = 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($db) {
    try {
        $productsCollection = $db->products;
        $ordersCollection = $db->orders;
        $usersCollection = $db->users;

        // 2. FETCH PRODUCTS (With Search Logic)
        $query = [];
        if (!empty($search_term)) {
            // Case-insensitive search for title
            $query['title'] = ['$regex' => $search_term, '$options' => 'i'];
        }
        $products = $productsCollection->find($query, ['sort' => ['id' => 1]])->toArray();
        
        // Fetch All Orders
        $orders = $ordersCollection->find([], ['sort' => ['order_date' => -1]])->toArray();
        
        // Calculate Stats
        foreach ($orders as $order) {
            $order = (array)$order;
            if (isset($order['total_price'])) $total_sales += $order['total_price'];
            if (isset($order['items'])) {
                foreach ($order['items'] as $item) {
                    $item = (array)$item;
                    if (isset($item['co2_saved'])) $total_co2 += ($item['co2_saved'] * $item['quantity']);
                }
            }
        }
        
        // Count Users
        $total_users = $usersCollection->countDocuments(['role' => 'customer']);
    } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
        error_log('MongoDB timeout in admin.php: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('MongoDB error in admin.php: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Leaf Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page-body">
    
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.php" class="brand"><i class="fas fa-leaf"></i> Leaf Admin</a>
        <a href="#" class="menu-item active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="#products" class="menu-item"><i class="fas fa-box"></i> Products</a>
        <a href="#orders" class="menu-item"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="#users" class="menu-item"><i class="fas fa-users"></i> Users</a>
        <a href="logout.php" class="menu-item" style="margin-top: auto;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <h1 style="font-size: 32px; color: #1a4d2e; margin-bottom: 30px; font-weight: 700;">Dashboard Overview</h1>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo count($products); ?></h3>
                <p>Total Products</p>
            </div>
            <div class="stat-card">
                <h3><?php echo number_format($total_sales, 2); ?> TL</h3>
                <p>Total Revenue</p>
            </div>
            <div class="stat-card">
                <h3>-<?php echo number_format($total_co2, 1); ?> kg</h3>
                <p>CO₂ Saved</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_users; ?></h3>
                <p>Customers</p>
            </div>
        </div>

        <!-- Products Panel with Search -->
        <div class="panel" id="products">
            <div class="panel-header">
                <h2>Product Management</h2>
                
                <!-- Search Form -->
                <form action="admin.php" method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>

                <button onclick="document.getElementById('addModal').style.display='block'" class="btn-add">+ Add Product</button>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="7" style="text-align:center; color:#999;">No products found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <?php $p = (array)$p; // Ensure array access ?>
                            <tr>
                                <td>#<?php echo isset($p['id']) ? $p['id'] : '?'; ?></td>
                                <td><img src="<?php echo isset($p['image']) ? $p['image'] : ''; ?>" width="40" height="40" style="object-fit:cover; border-radius:4px;"></td>
                                <td><strong><?php echo isset($p['title']) ? $p['title'] : 'Unknown'; ?></strong></td>
                                <td><?php echo isset($p['category']) ? $p['category'] : '-'; ?></td>
                                <td><?php echo isset($p['price']) ? $p['price'] : 0; ?> TL</td>
                                <td>
                                    <span style="color: <?php echo ($p['stock'] < 5) ? 'red' : 'green'; ?>; font-weight:bold;">
                                        <?php echo isset($p['stock']) ? $p['stock'] : 0; ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Delete button removed -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Orders Panel -->
        <div class="panel" id="orders">
            <div class="panel-header">
                <h2>Recent Orders</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <?php $order = (array)$order; ?>
                        <tr>
                            <td>#<?php echo substr((string)$order['_id'], -6); ?></td>
                            <td><?php echo isset($order['user_name']) ? $order['user_name'] : 'Guest'; ?></td>
                            <td><?php echo number_format(isset($order['total_price']) ? $order['total_price'] : 0, 2); ?> TL</td>
                            <td>
                                <?php 
                                    if(isset($order['order_date']) && $order['order_date'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $order['order_date']->toDateTime()->format('M d, Y H:i'); 
                                    } else { echo "Date Unknown"; }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Users Panel -->
        <div class="panel" id="users">
            <div class="panel-header">
                <h2>User Management</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($db) {
                        $allUsers = $db->users->find([], ['sort' => ['created_at' => -1]])->toArray();
                        foreach ($allUsers as $user):
                            $user = (array)$user;
                    ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($user['name'] ?? 'Unknown'); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                            <td>
                                <span style="
                                    padding: 4px 12px; 
                                    border-radius: 12px; 
                                    font-size: 11px; 
                                    font-weight: 600; 
                                    text-transform: uppercase;
                                    <?php 
                                    if ($user['role'] === 'admin') echo 'background: #fee2e2; color: #991b1b;';
                                    elseif ($user['role'] === 'seller') echo 'background: #dbeafe; color: #1e40af;';
                                    else echo 'background: #dcfce7; color: #166534;';
                                    ?>
                                ">
                                    <?php echo htmlspecialchars($user['role'] ?? 'customer'); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    if(isset($user['created_at']) && $user['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $user['created_at']->toDateTime()->format('M d, Y'); 
                                    } else { echo "Unknown"; }
                                ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Add Product Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
            <h2>Add New Product</h2>
            <form action="admin_action.php" method="POST">
                <input type="hidden" name="action" value="add_product">
                
                <div class="form-group">
                    <label>Title (English)</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option value="fresh_produce">Fresh Produce</option>
                        <option value="dairy_eggs">Dairy & Eggs</option>
                        <option value="bakery">Bakery</option>
                        <option value="pantry">Pantry</option>
                        <option value="beverages">Beverages</option>
                        <option value="home_garden">Home & Garden</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image" class="form-control" placeholder="e.g. https://images.unsplash.com/..." required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="desc" class="form-control" rows="3" placeholder="Product description..."></textarea>
                </div>
                <div class="form-group">
                    <label>Seller</label>
                    <select name="seller_name" class="form-control" required>
                        <option value="">-- Select Seller --</option>
                        <?php 
                        if ($db) {
                            $sellers = $db->users->find(['role' => 'seller'], ['sort' => ['name' => 1]])->toArray();
                            foreach ($sellers as $s) {
                                $s = (array)$s;
                                echo '<option value="' . htmlspecialchars($s['name']) . '">' . htmlspecialchars($s['name']) . ' (' . htmlspecialchars($s['location'] ?? 'Unknown') . ')</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Delivery Mode</label>
                    <select name="delivery_mode" class="form-control">
                        <option value="bike">Bike Courier</option>
                        <option value="walk">Walking Courier</option>
                        <option value="public">Public Transport</option>
                        <option value="cargo">Standard Cargo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Packaging Type</label>
                    <select name="packaging_type" class="form-control">
                        <option value="plastic_free">Plastic-Free</option>
                        <option value="recycled">Recycled Paper</option>
                        <option value="standard">Standard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>CO₂ Saved (kg)</label>
                    <input type="number" step="0.1" name="co2_saved" class="form-control" value="0.3">
                </div>

                <button type="submit" class="btn-add" style="width:100%;">Save Product</button>
            </form>
        </div>
    </div>

</body>
</html>