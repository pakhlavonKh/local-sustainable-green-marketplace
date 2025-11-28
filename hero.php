<?php require_once 'lang_config.php'; ?>

<!-- HERO SLIDER SECTION -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,500&display=swap" rel="stylesheet">

<!-- Main Container: Deep Forest Green background -->
<section class="hero-section" style="position: relative; height: 85vh; min-height: 600px; width: 100%; overflow: hidden; background-color: #1a2620;">
    
    <!-- SLIDE 1: The Mission -->
    <div class="hero-slide active">
        <!-- Image -->
        <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1472214103451-9374bd1c798e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div>
        <div class="hero-overlay"></div>
        
        <div class="hero-content">
            <h1 class="hero-title"><?php echo $text['slide1_title']; ?></h1>
            <p class="hero-goal">
                <?php echo $text['slide1_text']; ?>
            </p>
            <div class="quote-box">
                <p class="hero-quote"><?php echo $text['slide1_quote']; ?></p>
                <p class="quote-author"><?php echo $text['slide1_author']; ?></p>
            </div>
        </div>
    </div>

    <!-- SLIDE 2: The Community -->
    <div class="hero-slide">
        <!-- Image -->
        <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div>
        <div class="hero-overlay"></div>
        
        <div class="hero-content">
            <h1 class="hero-title"><?php echo $text['slide2_title']; ?></h1>
            <p class="hero-goal">
                <?php echo $text['slide2_text']; ?>
            </p>
            <div class="quote-box">
                <p class="hero-quote"><?php echo $text['slide2_quote']; ?></p>
                <p class="quote-author"><?php echo $text['slide2_author']; ?></p>
            </div>
        </div>
    </div>

    <!-- SLIDE 3: The Categories Overview -->
    <div class="hero-slide">
        <!-- Image -->
        <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1488459716781-31db52582fe9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div>
        <div class="hero-overlay"></div> 
        
        <div class="hero-content" style="max-width: 800px;">
            <h1 class="hero-title"><?php echo $text['slide3_title']; ?></h1>
            <p class="hero-subtitle" style="font-size: 1.5rem; font-weight: bold; color: #b8ffce; margin-bottom: 1rem;">
                <?php echo $text['slide3_sub1']; ?>
            </p>
            <p class="hero-subtitle" style="color: #f0f0f0;"><?php echo $text['slide3_sub2']; ?></p>
            <!-- Targeted Button for Pause Logic -->
            <a href="#catalog-anchor" class="hero-btn"><?php echo $text['slide3_btn']; ?></a>
        </div>
    </div>

</section>

<!-- INTERNAL STYLES -->
<style>
    /* Slide Layout */
    .hero-slide {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        display: flex;
        align-items: center;
        transform: translateX(100%);
        transition: transform 1s ease-in-out; 
    }

    .hero-slide.active { transform: translateX(0); z-index: 2; }
    .hero-slide.exit { transform: translateX(-100%); z-index: 1; }

    /* Background Image */
    .slide-bg {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-size: cover;
        background-position: center;
    }

    /* Overlay */
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.1) 100%);
        z-index: 1;
    }

    /* Content Box */
    .hero-content {
        position: relative;
        z-index: 10;
        padding-left: 10%;
        padding-right: 10%;
        max-width: 900px;
        text-align: left;
        color: white;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 25px;
        color: white;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .hero-goal {
        font-size: 1.25rem;
        line-height: 1.6;
        color: #f0f0f0;
        margin-bottom: 40px;
        max-width: 600px;
        font-weight: 400;
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    /* Quote Box */
    .quote-box {
        border-left: 4px solid #b8ffce;
        padding-left: 15px;
        margin-top: 20px;
        max-width: 500px;
        opacity: 0.95;
    }

    .hero-quote {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-style: italic;
        color: white;
        margin-bottom: 5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    .quote-author {
        font-family: 'Poppins', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #e0e0e0;
    }
    
    .hero-btn {
        display: inline-block;
        padding: 12px 30px;
        background-color: white;
        color: #1a5c34;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        margin-top: 20px;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .hero-btn:hover {
        background-color: #2daa59;
        color: white;
        transform: translateY(-3px);
    }
</style>

<!-- Slider Logic with Pause-on-Hover ONLY for the Button -->
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    // We select the SPECIFIC button instead of the whole section
    const categoryBtn = document.querySelector('.hero-btn');
    let slideInterval; 

    function nextSlide() {
        if(slides.length > 0) {
            const outgoingSlide = slides[currentSlide];
            outgoingSlide.classList.remove('active');
            outgoingSlide.classList.add('exit');

            currentSlide = (currentSlide + 1) % slides.length;
            const incomingSlide = slides[currentSlide];

            incomingSlide.classList.remove('exit');
            void incomingSlide.offsetWidth; 
            incomingSlide.classList.add('active');
        }
    }

    function startSlider() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 8000);
    }

    function stopSlider() {
        clearInterval(slideInterval);
    }

    startSlider();

    // Event Listeners for Pause-on-Hover
    // Only apply if the button exists (it's on slide 3)
    if (categoryBtn) {
        // Stop slider ONLY when hovering the button
        categoryBtn.addEventListener('mouseenter', stopSlider);
        // Restart slider when mouse leaves the button
        categoryBtn.addEventListener('mouseleave', startSlider);
    }
</script>