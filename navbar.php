<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'lang_config.php'; 

// Determine Profile Link based on Role
$profile_link = 'profile.php';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $profile_link = 'seller_dashboard.php';
}
?>

<nav class="navbar">
    <div class="nav-top">
        <div class="logo"><a href="index.php">Leaf Leaf Market</a></div>
        
        <form action="search.php" method="GET" class="search-bar">
            <input type="text" name="q" placeholder="<?php echo isset($text['search_place']) ? $text['search_place'] : 'Search...'; ?>" required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>

        <div class="user-actions">
            <a href="#"><i class="far fa-heart"></i></a>
            
            <?php if(isset($_SESSION['user_id']) && !empty($_SESSION['username'])): ?>
                <span class="user-welcome" style="margin-left: 15px; font-size: 14px;">
                    <i class="far fa-user" style="color:white;"></i> 
                    <a href="<?php echo $profile_link; ?>" style="color: white; text-decoration: none; font-weight: bold;">
                        Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                </span>
                <a href="logout.php" style="font-size: 12px; margin-left: 5px; opacity: 0.8; color: white; text-decoration: underline;">(Logout)</a>
            <?php else: ?>
                <a href="login.php"><i class="far fa-user"></i> <?php echo isset($text['nav_login']) ? $text['nav_login'] : 'Log In'; ?></a>
            <?php endif; ?>

            <a href="cart.php" class="basket-btn"><i class="fas fa-shopping-basket"></i> <?php echo isset($text['basket']) ? $text['basket'] : 'Basket'; ?></a>
        </div>
    </div>

    <div class="nav-bottom">
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> <?php echo isset($text['nav_home']) ? $text['nav_home'] : 'Home'; ?></a></li>
            <li><a href="category_page.php?category=fresh_produce"><?php echo isset($text['cat_fresh']) ? $text['cat_fresh'] : 'Fresh Produce'; ?></a></li>
            <li><a href="category_page.php?category=dairy_eggs"><?php echo isset($text['cat_dairy']) ? $text['cat_dairy'] : 'Dairy & Eggs'; ?></a></li>
            <li><a href="category_page.php?category=bakery"><?php echo isset($text['cat_bakery']) ? $text['cat_bakery'] : 'Bakery'; ?></a></li>
            <li><a href="category_page.php?category=pantry"><?php echo isset($text['cat_pantry']) ? $text['cat_pantry'] : 'Pantry'; ?></a></li>
            <li><a href="category_page.php?category=beverages"><?php echo isset($text['cat_bev']) ? $text['cat_bev'] : 'Beverages'; ?></a></li>
            <li><a href="category_page.php?category=home_garden"><?php echo isset($text['cat_home']) ? $text['cat_home'] : 'Home & Garden'; ?></a></li>
            <li class="nav-special"><a href="about.php"><?php echo isset($text['nav_about']) ? $text['nav_about'] : 'About'; ?></a></li>
        </ul>
    </div>
</nav>

<style>
.navbar { font-family: 'Arial', sans-serif; }
.nav-top { background: #1a4d2e; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
.logo a { color: #fff; text-decoration: none; font-size: 24px; font-family: serif; }
.search-bar { display: flex; background: #2f6142; border-radius: 25px; padding: 5px 15px; width: 40%; border: none; }
.search-bar input { background: transparent; border: none; color: #fff; width: 100%; outline: none; }
.search-bar button { background: #4ade80; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.user-actions a { color: #fff; text-decoration: none; margin-left: 20px; font-size: 14px; }
.basket-btn { background: #2f6142; padding: 8px 15px; border-radius: 20px; }
.nav-bottom { background: #fdfbf7; border-bottom: 1px solid #eee; padding: 15px 40px; }
.nav-bottom ul { list-style: none; padding: 0; margin: 0; display: flex; gap: 30px; justify-content: center; }
.nav-bottom a { text-decoration: none; color: #333; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.nav-special a { color: #1a4d2e; }
</style>