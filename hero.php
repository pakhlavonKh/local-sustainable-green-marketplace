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