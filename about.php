<?php
require 'config.php';
require 'lang_config.php';
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
            <span class="eyebrow-text"><?php echo htmlspecialchars($text['about_eyebrow']); ?></span>
            <h1 class="main-headline">
                <?php echo htmlspecialchars($text['about_headline1']); ?> <br>
                <span class="italic-accent"><?php echo htmlspecialchars($text['about_headline2']); ?></span>
            </h1>
            <div class="intro-text-block">
                <p>
                    <?php echo htmlspecialchars($text['about_intro']); ?>
                </p>
            </div>
        </header>

        <!-- 2. SECTION 1: Text Left, Image Right -->
        <section class="shifted-section">
            <div class="shifted-text-block">
                <h2 class="section-title"><?php echo htmlspecialchars($text['about_section1_title']); ?></h2>
                <p class="section-body">
                    <?php echo htmlspecialchars($text['about_section1_text']); ?>
                </p>
                <a href="#" class="read-more-link"><?php echo htmlspecialchars($text['about_section1_link']); ?></a>
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
                <h2 class="section-title"><?php echo htmlspecialchars($text['about_section2_title']); ?></h2>
                <p class="section-body">
                    <?php echo htmlspecialchars($text['about_section2_text']); ?>
                </p>
                <a href="#" class="read-more-link"><?php echo htmlspecialchars($text['about_section2_link']); ?></a>
            </div>
        </section>

        <!-- 4. QUOTE (Bottom) -->
        <section class="quote-section">
            <h3 class="handwriting-quote"><?php echo htmlspecialchars($text['about_quote']); ?></h3>
            <span class="quote-author"><?php echo htmlspecialchars($text['about_quote_author']); ?></span>
        </section>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>