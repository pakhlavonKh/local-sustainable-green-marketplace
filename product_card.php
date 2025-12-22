<?php
/**
 * Reusable Product Card Component
 * 
 * Usage:
 * include 'product_card.php';
 * renderProductCard($product, $options);
 * 
 * Parameters:
 * - $product: array with product data (id, title, price, image, etc.)
 * - $options: array with optional settings:
 *   - 'show_wishlist' => bool (default: true)
 *   - 'show_add_to_cart' => bool (default: true)
 *   - 'show_delete' => bool (default: false)
 *   - 'is_owner' => bool (default: false)
 *   - 'class' => string (additional CSS classes)
 *   - 'link_to_detail' => bool (default: true)
 */

function renderProductCard($product, $options = []) {
    // Ensure translations are available
    global $text;
    if (!isset($text)) {
        $text = [];
    }
    
    // Default options
    $defaults = [
        'show_wishlist' => true,
        'show_add_to_cart' => true,
        'show_delete' => false,
        'is_owner' => false,
        'class' => '',
        'link_to_detail' => true,
        'redirect_after_delete' => ''
    ];
    $options = array_merge($defaults, $options);
    
    // Extract product data
    $id = $product['id'] ?? 0;
    $title = $product['title'] ?? 'Product';
    $title_tr = $product['title_tr'] ?? $title;
    $price = $product['price'] ?? 0;
    $stock = $product['stock'] ?? 0;
    $image = $product['image'] ?? 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=500';
    $seller_name = $product['seller_name'] ?? 'Unknown Seller';
    $co2_saved = $product['co2_saved'] ?? 0;
    
    // Language support
    $lang = $_SESSION['lang'] ?? 'en';
    $display_title = ($lang === 'tr' && !empty($title_tr)) ? $title_tr : $title;
    
    // Currency
    $currency = $text['currency'] ?? 'TL';
    ?>
    
    <div class="product-card <?php echo htmlspecialchars($options['class']); ?>">
        <?php if ($options['link_to_detail']): ?>
            <a href="javascript:void(0);" onclick="openProductModal(<?php echo $id; ?>)" style="text-decoration:none; color:inherit;">
        <?php endif; ?>
        
        <div class="p-img-wrapper">
            <div class="p-img" style="background-image: url('<?php echo htmlspecialchars($image); ?>');"></div>
            
            <?php if ($options['show_wishlist']): ?>
                <button class="wishlist-btn" data-product-id="<?php echo $id; ?>" 
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(event, <?php echo $id; ?>)">
                    <i class="far fa-heart"></i>
                </button>
            <?php endif; ?>
            
            <?php if ($options['show_delete'] || $options['is_owner']): ?>
                <div class="product-actions-overlay">
                    <button class="btn-edit-overlay" 
                            onclick="event.stopPropagation(); openEditModal(<?php echo $id; ?>, <?php echo htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8'); ?>);"
                            title="Edit product">
                        <i class="fas fa-edit"></i>
                    </button>
                    <a href="seller_action.php?action=delete&id=<?php echo $id; ?><?php echo $options['redirect_after_delete'] ? '&redirect=' . urlencode($options['redirect_after_delete']) : ''; ?>" 
                       class="btn-delete-overlay" 
                       onclick="event.stopPropagation(); return confirm('Delete this product?');">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($stock < 5 && $stock > 0): ?>
                <span class="badge-low-stock">
                    <?php echo $text['low_stock'] ?? 'Low Stock'; ?> (<?php echo $stock; ?> left)
                </span>
            <?php elseif ($stock == 0): ?>
                <span class="badge-out-of-stock">
                    <?php echo $text['out_of_stock'] ?? 'Out of Stock'; ?>
                </span>
            <?php endif; ?>
        </div>
        
        <?php if ($options['link_to_detail']): ?>
            </a>
        <?php endif; ?>
        
        <div class="p-info">
            <div>
                <span class="p-title"><?php echo htmlspecialchars($display_title); ?></span>
                <span class="p-seller">by <?php echo htmlspecialchars($seller_name); ?></span>
                <?php if ($stock > 0): ?>
                    <span class="stock-indicator" style="font-size: 11px; color: <?php echo $stock < 10 ? '#d97706' : '#16a34a'; ?>; display: block; margin-top: 2px;">
                        <i class="fas fa-box"></i> <?php echo $stock; ?> in stock
                    </span>
                <?php elseif ($stock == 0): ?>
                    <span class="stock-indicator" style="font-size: 11px; color: #dc2626; display: block; margin-top: 2px;">
                        <i class="fas fa-times-circle"></i> Out of stock
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="p-footer">
                <span class="p-price"><?php echo number_format((float)$price, 2); ?> <?php echo htmlspecialchars($currency); ?></span>
                
                <?php if ($options['show_add_to_cart']): ?>
                    <?php if ($stock > 0): ?>
                        <form action="cart_action.php" method="POST" onclick="event.stopPropagation();" onsubmit="return handleQuickAddToCart(event, <?php echo $id; ?>, '<?php echo htmlspecialchars($display_title, ENT_QUOTES); ?>');">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="title" value="<?php echo htmlspecialchars($display_title); ?>">
                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($image); ?>">
                            <input type="hidden" name="co2" value="<?php echo $co2_saved; ?>">
                            <input type="hidden" name="stock" value="<?php echo $stock; ?>">
                            <button type="submit" class="add-btn" title="Add to Cart">+</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="add-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Out of Stock">✕</button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php
}
?>
