<?php
require_once 'lang_config.php';
require_once 'data_products.php';

$carousel_items = array_values($products_db);
shuffle($carousel_items);
$carousel_items = array_slice($carousel_items, 0, 12);

$lang = $_SESSION['lang'] ?? 'en';
?>

<div class="carousel-container">
    <div class="carousel-header">
        <h2>
            <?php echo htmlspecialchars($text['carousel_title'] ?? 'You Might Be Interested In'); ?>
        </h2>

        <!-- MATCHES .carousel-arrows FROM CSS -->
        <div class="carousel-arrows">
            <span class="carousel-prev" aria-label="Previous">&larr;</span>
            <span class="carousel-next" aria-label="Next">&rarr;</span>
        </div>
    </div>

    <!-- MATCHES .carousel-track FROM CSS -->
    <div class="carousel-track">
        <?php foreach ($carousel_items as $item): ?>
            <?php
                $id    = (int)($item['id'] ?? 0);
                $price = $item['price'] ?? 0;
                $stock = $item['stock'] ?? 0;
                $img   = $item['image'] ?? 'assets/img/placeholder.png';
                $title = $item['title_' . $lang] ?? $item['title'] ?? 'Product';
            ?>
            <div class="carousel-card">
                <a href="javascript:void(0);" onclick="openProductModal(<?php echo $id; ?>)" class="carousel-link">
                    <div class="carousel-image-wrapper">
                        <img
                            src="<?php echo htmlspecialchars($img); ?>"
                            alt="<?php echo htmlspecialchars($title); ?>"
                            loading="lazy"
                        >
                        <?php if ($stock < 5): ?>
                            <!-- MATCHES .badge-low-stock IN CSS -->
                            <span class="badge-low-stock">
                                <?php echo htmlspecialchars($text['low_stock'] ?? 'Low Stock'); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- MATCHES .carousel-info, .price-row, .price -->
                    <div class="carousel-info">
                        <h3><?php echo htmlspecialchars($title); ?></h3>
                        <div class="price-row">
                            <span class="price">
                                <?php echo number_format((float)$price, 2); ?>
                                <?php echo htmlspecialchars($text['currency'] ?? 'TL'); ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MATCHES .carousel-dots / .carousel-dot -->
    <div class="carousel-dots"></div>
    
    <!-- See More Link -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="category_page.php?category=all" class="carousel-see-more">
            <?php echo htmlspecialchars($text['shop_all'] ?? 'See All Products'); ?>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<script>
// JS adapted to your CSS structure (.carousel-arrows, .carousel-track, .carousel-dot)
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".carousel-container").forEach(container => {
        const track = container.querySelector(".carousel-track");
        const dotsContainer = container.querySelector(".carousel-dots");
        const arrows = container.querySelectorAll(".carousel-arrows span");
        const prevBtn = arrows[0];
        const nextBtn = arrows[1];

        const cards = Array.from(track.children);
        if (cards.length === 0) return;

        // GAP should match CSS .carousel-track gap
        const getGap = () => {
            const style = window.getComputedStyle(track);
            return parseFloat(style.columnGap || style.gap || "25") || 25;
        };

        let gap = getGap();
        let cardWidth = cards[0].offsetWidth + gap;

        const recalc = () => {
            gap = getGap();
            cardWidth = cards[0].offsetWidth + gap;
        };

        // Create dots based on how many "pages" we have
        const createDots = () => {
            dotsContainer.innerHTML = "";

            const visibleCards = Math.max(
                1,
                Math.floor(track.clientWidth / (cards[0].offsetWidth + gap))
            );
            const totalPages = Math.ceil(cards.length / visibleCards);

            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement("button");
                dot.className = "carousel-dot";
                if (i === 0) dot.classList.add("active");
                dot.addEventListener("click", () => {
                    const scrollPosition = i * visibleCards * cardWidth;
                    track.scrollTo({
                        left: scrollPosition,
                        behavior: "smooth"
                    });
                });
                dotsContainer.appendChild(dot);
            }
        };

        const updateDots = () => {
            const visibleCards = Math.max(
                1,
                Math.floor(track.clientWidth / cardWidth)
            );
            const pageWidth = visibleCards * cardWidth;
            const currentPage = Math.round(track.scrollLeft / pageWidth);
            const dots = Array.from(dotsContainer.children);

            dots.forEach((dot, i) => {
                dot.classList.toggle("active", i === currentPage);
            });

            // Disable arrows at edges (optional)
            if (prevBtn) {
                prevBtn.disabled = track.scrollLeft <= 0;
            }
            if (nextBtn) {
                nextBtn.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 5;
            }
        };

        const scrollByPage = direction => {
            const visibleCards = Math.max(
                1,
                Math.floor(track.clientWidth / cardWidth)
            );
            const pageWidth = visibleCards * cardWidth;
            track.scrollBy({
                left: direction * pageWidth,
                behavior: "smooth"
            });
        };

        if (prevBtn) {
            prevBtn.addEventListener("click", () => scrollByPage(-1));
        }
        if (nextBtn) {
            nextBtn.addEventListener("click", () => scrollByPage(1));
        }

        // Throttled scroll listener
        let ticking = false;
        track.addEventListener("scroll", () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    updateDots();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Initial layout + dots
        setTimeout(() => {
            recalc();
            createDots();
            updateDots();
        }, 100);

        // On resize: recompute layout + redraw dots
        window.addEventListener("resize", () => {
            setTimeout(() => {
                recalc();
                createDots();
                updateDots();
            }, 150);
        });
    });
});
</script>
