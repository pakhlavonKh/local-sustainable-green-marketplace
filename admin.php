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
        body { font-family: 'Arial', sans-serif; background: #f1f5f9; margin: 0; display: flex; }
        
        /* Sidebar */
        .sidebar { width: 250px; background: #1a4d2e; color: white; min-height: 100vh; padding: 20px; position: fixed; }
        .brand { font-size: 24px; font-weight: bold; margin-bottom: 40px; display: block; color: white; text-decoration: none; }
        .menu-item { display: block; color: #cbd5e1; text-decoration: none; padding: 12px; margin-bottom: 5px; border-radius: 5px; transition: 0.2s; }
        .menu-item:hover, .menu-item.active { background: #14532d; color: white; }
        .menu-item i { margin-right: 10px; width: 20px; }
        
        /* Main Content */
        .main-content { margin-left: 250px; padding: 30px; width: 100%; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .stat-card h3 { margin: 0; font-size: 28px; color: #1e293b; }
        .stat-card p { margin: 5px 0 0; color: #64748b; font-size: 14px; text-transform: uppercase; }
        
        /* Tables & Search */
        .panel { background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 20px; margin-bottom: 40px; }
        
        .panel-header { 
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .search-box input { border: none; background: transparent; outline: none; padding: 5px; font-size: 14px; width: 200px; }
        .search-box button { background: none; border: none; cursor: pointer; color: #666; }

        h2 { margin: 0; color: #1e293b; font-size: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px; background: #f8fafc; color: #64748b; font-size: 12px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #333; font-size: 14px; }
        
        .btn-add { background: #1a4d2e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 14px; border: none; cursor: pointer; }
        .btn-action { padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; margin-right: 5px; }
        .btn-edit { background: #e0f2fe; color: #0284c7; }
        .btn-delete { background: #fee2e2; color: #991b1b; }
        
        /* Modal for Adding Product */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }

        <a href="index.php" class="brand">Leaf Admin</a>
        <a href="#" class="menu-item active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="#products" class="menu-item"><i class="fas fa-box"></i> Products</a>
        <a href="#orders" class="menu-item"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="logout.php" class="menu-item" style="margin-top: auto;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        
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
                                    <a href="admin_action.php?action=delete&id=<?php echo $p['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
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
                    <label>Image URL (Unsplash ID)</label>
                    <input type="text" name="image" class="form-control" placeholder="e.g. https://images.unsplash.com/..." required>
                </div>
                <div class="form-group">
                    <label>Seller Name</label>
                    <input type="text" name="seller_name" class="form-control" value="Leaf Market" required>
                </div>

                <button type="submit" class="btn-add" style="width:100%;">Save Product</button>
            </form>
        </div>
    </div>

</body>
</html>