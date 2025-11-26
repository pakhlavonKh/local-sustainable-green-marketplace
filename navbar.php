<!-- TOP GOAL BAR (Does not stick, scrolls away) -->
<div class="top-goal-bar">
    <div class="container">
        <span class="goal-item"><i class="fas fa-leaf"></i> 100% Sustainable Sourcing</span>
        <span class="goal-item"><i class="fas fa-truck-fast"></i> Low Carbon Delivery</span>
        <span class="goal-item"><i class="fas fa-hand-holding-heart"></i> Supporting Local Farmers</span>
    </div>
</div>

<!-- STICKY WRAPPER: Holds both the Main Nav and Category Nav -->
<div class="sticky-header-wrapper">
    
    <!-- MAIN NAVBAR: Forest Green & Chic -->
    <nav class="navbar">
        <div class="container navbar-container">
            
            <!-- LOGO -->
            <a href="index.php" class="navbar-brand">
                Leaf Leaf <span style="font-weight: 300; font-size: 0.9em;">Market</span>
            </a>

            <!-- SEARCH BAR -->
            <div class="search-wrapper">
                <form action="index.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search for fresh greens, dairy..." class="search-input">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- ICONS -->
            <div class="nav-icons">
                <a href="#" class="icon-link tooltip" data-tooltip="Favorites"><i class="far fa-heart"></i></a>
                <a href="login.php" class="icon-link tooltip" data-tooltip="Account"><i class="far fa-user"></i></a>
                <a href="cart.php" class="icon-link cart-link tooltip" data-tooltip="My Basket">
                    <div class="icon-wrapper">
                        <i class="fas fa-shopping-basket"></i>
                        <span id="cart-count" class="cart-badge">0</span>
                    </div>
                    <span class="link-text">Basket</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- NEW SECONDARY NAVBAR (Hermes Style) -->
    <div class="secondary-nav">
        <div class="container">
            <ul class="category-links">
                <!-- Adapted Categories for Leaf Market -->
                <li><a href="index.php?category=produce">Fresh Produce</a></li>
                <li><a href="index.php?category=dairy">Dairy & Eggs</a></li>
                <li><a href="index.php?category=bakery">Bakery</a></li>
                <li><a href="index.php?category=pantry">Pantry</a></li>
                <li><a href="index.php?category=beverages">Beverages</a></li>
                <li><a href="index.php?category=home">Home & Garden</a></li>
                
                <!-- Special Divider -->
                <li class="nav-divider">|</li>
                
                <!-- About Section -->
                <li><a href="about.php" class="highlight-link">About Leaf Market</a></li>
            </ul>
        </div>
    </div>

</div>