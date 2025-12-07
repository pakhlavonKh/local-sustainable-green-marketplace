<?php
require_once 'lang_config.php';
require_once 'data_products.php';

// ... (Your existing shuffling logic stays the same) ...
$carousel_items = array_values($products_db); // Convert to list
shuffle($carousel_items);
$carousel_items = array_slice($carousel_items, 0, 8);
?>

<div class="carousel-container">
    <div class="carousel-header">
        <h2><?php echo isset($text['carousel_title']) ? $text['carousel_title'] : 'You Might Be Interested In'; ?></h2>
        <div class="carousel-arrows"><span>&larr;</span><span>&rarr;</span></div>
    </div>

    <div class="carousel-track">
        <?php foreach ($carousel_items as $item): ?>
            <?php 
                $id = $item['id'];
                // --- NEW TRANSLATION LOGIC ---
                $display_title = ($_SESSION['lang'] == 'tr' && !empty($item['title_tr'])) 
                                 ? $item['title_tr'] 
                                 : $item['title'];

                $price = $item['price'];
                $stock = $item['stock']; 
                $img = $item['image'];
            ?>
            <div class="carousel-card">
                <a href="product_detail.php?id=<?php echo $id; ?>" class="carousel-link">
                    <div class="carousel-image-wrapper">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="product">
                        <?php if($stock < 5): ?>
                            <span class="badge-low-stock"><?php echo isset($text['low_stock']) ? $text['low_stock'] : 'Low Stock'; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="carousel-info">
                        <h3><?php echo htmlspecialchars($display_title); ?></h3>
                        <div class="price-row">
                            <span class="price">
                                <?php echo number_format($price, 2); ?> 
                                <?php echo isset($text['currency']) ? $text['currency'] : 'TL'; ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Dots / pagination indicators -->
    <div class="carousel-dots" aria-hidden="false"></div>
</div>

<script>
// Initialize carousels on the page (support multiple instances)
(function(){
    const containers = document.querySelectorAll('.carousel-container');
    if(!containers || containers.length === 0) return;

    containers.forEach(container => {
        const track = container.querySelector('.carousel-track, .product-carousel');
        if(!track) return;
        const cards = Array.from(track.querySelectorAll('.carousel-card'));
        let dotsWrap = container.querySelector('.carousel-dots');
        const leftArrow = container.querySelector('.carousel-arrows span:first-child');
        const rightArrow = container.querySelector('.carousel-arrows span:last-child');
        const gap = parseInt(getComputedStyle(track).gap) || 25;

        function cardWidth() {
            if(cards.length === 0) return 300;
            const w = cards[0].getBoundingClientRect().width;
            return w + gap;
        }

        function renderDots(){
            if(!dotsWrap){
                dotsWrap = document.createElement('div');
                dotsWrap.className = 'carousel-dots';
                track.parentNode.insertBefore(dotsWrap, track.nextSibling);
            }
            dotsWrap.innerHTML = '';
            const pageWidth = track.getBoundingClientRect().width;
            const cw = cardWidth();
            const perPage = Math.max(1, Math.floor((pageWidth + gap) / cw));
            const pages = Math.max(1, Math.ceil(cards.length / perPage));
            for(let i=0;i<pages;i++){
                const b = document.createElement('button');
                b.className = 'carousel-dot';
                b.setAttribute('data-index', i);
                b.addEventListener('click', ()=>{
                    track.scrollTo({left: i * perPage * cw, behavior:'smooth'});
                });
                dotsWrap.appendChild(b);
            }
            updateActiveDot();
        }

        function updateActiveDot(){
            if(!dotsWrap) return;
            const pageWidth = track.getBoundingClientRect().width;
            const cw = cardWidth();
            const perPage = Math.max(1, Math.floor((pageWidth + gap) / cw));
            const scrollLeft = track.scrollLeft + 5; // small offset
            const pageIndex = Math.floor(scrollLeft / (perPage * cw));
            const dots = Array.from(dotsWrap.querySelectorAll('.carousel-dot'));
            dots.forEach(d=>d.classList.remove('active'));
            if(dots[pageIndex]) dots[pageIndex].classList.add('active');
        }

        // arrows
        leftArrow && leftArrow.addEventListener('click', ()=>{
            track.scrollBy({left: -track.getBoundingClientRect().width, behavior:'smooth'});
        });
        rightArrow && rightArrow.addEventListener('click', ()=>{
            track.scrollBy({left: track.getBoundingClientRect().width, behavior:'smooth'});
        });

        track.addEventListener('scroll', ()=>{
            requestAnimationFrame(updateActiveDot);
        });

        window.addEventListener('resize', ()=>{
            renderDots();
        });

        // initial render
        renderDots();
    });
})();
</script>