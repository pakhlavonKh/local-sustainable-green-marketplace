<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Leaf Leaf Green Market</title>
    <!-- Force CSS refresh with v=12 -->
    <link rel="stylesheet" href="style.css?v=12"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="about-page">

    <?php include 'navbar.php'; ?>

    <div class="about-page-content">
        
        <!-- 1. INTRO HEADER -->
        <header class="about-header">
            <span class="eyebrow-text">ABOUT LEAF LEAF</span>
            <h1 class="main-headline">
                CULTIVATING SUSTAINABILITY <br>
                <span class="italic-accent">IN YOUR NEIGHBORHOOD</span>
            </h1>
            <div class="intro-text-block">
                <p>
                    Since 2025, Leaf Leaf Green Market has remained faithful to its artisanal model and its ecological values. 
                    The freedom to grow, the constant quest for sustainable materials, and the transmission of 
                    exceptional local produce forge the uniqueness of Leaf Leaf.
                </p>
            </div>
        </header>

        <!-- 2. SECTION 1: Text Left, Image Right -->
        <section class="shifted-section">
            <div class="shifted-text-block">
                <h2 class="section-title">A COMMUNITY SPIRIT</h2>
                <p class="section-body">
                    For the first generation of Leaf Leaf, we have been an independent, family-oriented platform. 
                    We believe that the best way to predict the future is to grow it yourself. Our entrepreneurial 
                    spirit drives us to connect local artisans with conscious consumers.
                </p>
                <a href="#" class="read-more-link">DISCOVER OUR MODEL</a>
            </div>
            <div class="shifted-image-container">
                <!-- Reliable Market Image -->
                <img class="shifted-image" src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80" alt="Community Spirit">
            </div>
        </section>

        <!-- 3. SECTION 2: Image Left, Text Right (Reverse Shift) -->
        <section class="shifted-section reverse">
            <div class="shifted-image-container">
                <!-- Reliable Nature/Field Image -->
                <img class="shifted-image" src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=800&q=80" alt="Ecological Commitment">
            </div>
            <div class="shifted-text-block">
                <h2 class="section-title">CREATIVE FREEDOM</h2>
                <p class="section-body">
                    Sustainability is not just a goal; it is our canvas. The sixteen categories of our market 
                    create collections that combine freedom with inventiveness. From hand-woven baskets to 
                    heirloom tomatoes, every object tells a story.
                </p>
                <a href="#" class="read-more-link">VIEW OUR VALUES</a>
            </div>
        </section>

        <!-- 4. QUOTE (Bottom) -->
        <section class="quote-section">
            <h3 class="handwriting-quote">"The Earth is what we all have in common."</h3>
            <span class="quote-author">— Wendell Berry</span>
        </section>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>