<?php require_once 'lang_config.php'; ?>

<nav class="navbar">
    <!-- Top Bar: Logo, Search, User Actions -->
    <div class="nav-top">
        <div class="logo">
            <a href="index.php">Leaf Leaf Market</a>
        </div>
        
        <div class="search-bar">
            <input type="text" placeholder="<?php echo $text['search_place']; ?>">
            <button><i class="fas fa-search"></i></button>
        </div>

        <div class="user-actions">
            <a href="#"><i class="far fa-heart"></i></a>
            <a href="login.php"><i class="far fa-user"></i> <?php echo $text['nav_login']; ?></a>
            <a href="cart.php" class="basket-btn">
                <i class="fas fa-shopping-basket"></i> <?php echo $text['basket']; ?>
            </a>
        </div>
    </div>

    <!-- Bottom Bar: Categories -->
    <div class="nav-bottom">
        <ul>
            <li><a href="index.php?category=fresh_produce"><?php echo $text['cat_fresh']; ?></a></li>
            <li><a href="index.php?category=dairy_eggs"><?php echo $text['cat_dairy']; ?></a></li>
            <li><a href="index.php?category=bakery"><?php echo $text['cat_bakery']; ?></a></li>
            <li><a href="index.php?category=pantry"><?php echo $text['cat_pantry']; ?></a></li>
            <li><a href="index.php?category=beverages"><?php echo $text['cat_bev']; ?></a></li>
            <li><a href="index.php?category=home_garden"><?php echo $text['cat_home']; ?></a></li>
            <li class="nav-special"><a href="about.php"><?php echo $text['nav_about']; ?></a></li>
        </ul>
    </div>
</nav>

<style>
/* Simplified Navbar Styles matching your screenshot */
.navbar { font-family: 'Arial', sans-serif; }
.nav-top { 
    background: #1a4d2e; /* Dark Green */
    padding: 15px 40px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
}
.logo a { color: #fff; text-decoration: none; font-size: 24px; font-family: serif; }
.search-bar { display: flex; background: #2f6142; border-radius: 25px; padding: 5px 15px; width: 40%; }
.search-bar input { background: transparent; border: none; color: #fff; width: 100%; outline: none; }
.search-bar button { background: #4ade80; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; }
.user-actions a { color: #fff; text-decoration: none; margin-left: 20px; font-size: 14px; }
.basket-btn { background: #2f6142; padding: 8px 15px; border-radius: 20px; }

.nav-bottom { background: #fdfbf7; border-bottom: 1px solid #eee; padding: 15px 40px; }
.nav-bottom ul { list-style: none; padding: 0; margin: 0; display: flex; gap: 30px; justify-content: center; }
.nav-bottom a { text-decoration: none; color: #333; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.nav-special a { color: #1a4d2e; }
</style>