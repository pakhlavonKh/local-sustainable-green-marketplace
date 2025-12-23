
function openProductModal(productId) {
    const modal = document.getElementById('productModal');
    const modalBody = document.getElementById('modalProductBody');
    
    if (!modal || !modalBody) return;
    
    modalBody.innerHTML = `
        <div style="text-align: center; padding: 60px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #1a4d2e;"></i>
            <p style="margin-top: 20px; color: #666;">Loading product details...</p>
        </div>
    `;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    fetch(`get_product_details.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                modalBody.innerHTML = `
                    <div style="text-align: center; padding: 60px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #dc2626;"></i>
                        <h3 style="margin-top: 20px;">Product Not Found</h3>
                        <button onclick="closeProductModal()" class="btn-main" style="margin-top: 20px;">Close</button>
                    </div>
                `;
                return;
            }
            
            renderProductModal(data);
        })
        .catch(error => {
            console.error('Error loading product:', error);
            modalBody.innerHTML = `
                <div style="text-align: center; padding: 60px;">
                    <i class="fas fa-exclamation-circle" style="font-size: 48px; color: #dc2626;"></i>
                    <h3 style="margin-top: 20px;">Failed to load product</h3>
                    <button onclick="closeProductModal()" class="btn-main" style="margin-top: 20px;">Close</button>
                </div>
            `;
        });
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function renderProductModal(product) {
    const modalBody = document.getElementById('modalProductBody');
    
    let stockBadge = '';
    let stockInfo = '';
    if (product.stock > 10) {
        stockBadge = `<span class="badge-in-stock"><i class="fas fa-check-circle"></i> In Stock</span>`;
        stockInfo = `<div style="background: #f0fdf4; padding: 15px; border-radius: 10px; border-left: 4px solid #22c55e; margin: 20px 0;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-box" style="color: #166534; font-size: 20px;"></i>
                <div>
                    <strong style="color: #166534;">In Stock</strong>
                    <span style="color: #166534; font-size: 14px;"> - ${product.stock} available</span>
                </div>
            </div>
        </div>`;
    } else if (product.stock > 0) {
        stockBadge = `<span class="badge-low-stock"><i class="fas fa-exclamation-triangle"></i> Only ${product.stock} left!</span>`;
        stockInfo = `<div style="background: #fef3c7; padding: 15px; border-radius: 10px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-box" style="color: #92400e; font-size: 20px;"></i>
                <div>
                    <strong style="color: #92400e;">Only ${product.stock} left in stock!</strong>
                    <span style="color: #92400e; font-size: 14px;"> - Order soon</span>
                </div>
            </div>
        </div>`;
    } else {
        stockBadge = `<span class="badge-out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>`;
        stockInfo = `<div style="background: #fee2e2; padding: 15px; border-radius: 10px; border-left: 4px solid #dc2626; margin: 20px 0;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-box" style="color: #991b1b; font-size: 20px;"></i>
                <div>
                    <strong style="color: #991b1b;">Out of Stock</strong>
                    <span style="color: #991b1b; font-size: 14px;"> - Currently unavailable</span>
                </div>
            </div>
        </div>`;
    }
    
    // Reviews section
    let reviewsHTML = '';
    if (product.reviews && product.reviews.length > 0) {
        reviewsHTML = product.reviews.map(review => {
            const stars = '★'.repeat(review.stars || 5) + '☆'.repeat(5 - (review.stars || 5));
            const replyHTML = review.reply ? `
                <div style="background:#f0fdf4; padding:10px; margin-top:10px; border-radius:8px; border-left:3px solid #166534;">
                    <strong style="color:#166534; font-size:11px; text-transform:uppercase;">Seller Response:</strong>
                    <p style="margin:5px 0 0 0; font-size:14px; color:#333;">${escapeHtml(review.reply)}</p>
                </div>
            ` : '';
            
            return `
                <div class="review-card">
                    <div class="review-header">
                        <span class="review-user">
                            ${escapeHtml(review.user || 'Customer')}
                            <span class="verified-badge">${product.text.verified_buyer}</span>
                        </span>
                        <div class="review-stars" style="color: #fbbf24;">${stars}</div>
                    </div>
                    <p class="review-comment">"${escapeHtml(review.comment || '')}"</p>
                    ${replyHTML}
                </div>
            `;
        }).join('');
    } else {
        reviewsHTML = '<p style="color: #666; font-style: italic;">No reviews yet. Be the first to review this sustainable product!</p>';
    }
    
    // Add to cart button
    let actionButton = '';
    if (product.stock > 0 && product.is_logged_in) {
        actionButton = `
            <form action="cart_action.php" method="POST" id="modalAddToCartForm" onsubmit="return handleModalCartSubmit(event, ${product.id}, '${escapeHtml(product.title)}')">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="${product.id}">
                <input type="hidden" name="title" value="${escapeHtml(product.title_raw)}">
                <input type="hidden" name="price" value="${product.price}">
                <input type="hidden" name="image" value="${escapeHtml(product.image)}">
                <input type="hidden" name="co2" value="${product.co2_saved}">
                <input type="hidden" name="stock" value="${product.stock}">
                
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label style="font-weight: 600; color: #333;">Qty:</label>
                        <input type="number" name="quantity" value="1" min="1" max="${product.stock}" 
                               style="width: 80px; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; font-weight: 600; text-align: center;">
                    </div>
                    
                    <button type="submit" class="btn-main" style="flex: 1;">
                        <i class="fas fa-shopping-basket"></i> ${product.text.add_cart}
                    </button>
                </div>
            </form>
        `;
    } else if (product.stock == 0) {
        actionButton = `
            <button class="btn-main" disabled style="opacity: 0.5; cursor: not-allowed; background: #9ca3af;">
                <i class="fas fa-times-circle"></i> Out of Stock
            </button>
        `;
    } else {
        actionButton = `
            <a href="login.php?redirect=${encodeURIComponent(window.location.pathname + window.location.search)}" class="btn-main">
                <i class="fas fa-lock"></i> ${product.text.nav_login}
            </a>
        `;
    }
    
    const sellerHTML = product.seller_id ? `
        <a href="seller_profile.php?id=${product.seller_id}" class="seller-card" style="text-decoration: none; color: inherit;">
            <div class="seller-icon"><i class="fas fa-store"></i></div>
            <div style="flex-grow: 1;">
                <strong style="display:block; color:#333;">${escapeHtml(product.seller_name)}</strong>
                <small style="color:#666;">
                    <i class="fas fa-map-marker-alt"></i> ${escapeHtml(product.distance)} away
                </small>
            </div>
            <div style="text-align:right;">
                <span style="background:#f3f4f6; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold; color:#1a4d2e;">
                    ${product.text.seller_profile} &rarr;
                </span>
            </div>
        </a>
    ` : `
        <div class="seller-card" style="cursor: default; opacity: 0.7;">
            <div class="seller-icon"><i class="fas fa-store"></i></div>
            <div style="flex-grow: 1;">
                <strong style="display:block; color:#333;">Leaf Market</strong>
                <small style="color:#666;">Direct from marketplace</small>
            </div>
        </div>
    `;
    
    modalBody.innerHTML = `
        <div class="modal-product-detail">
            <div class="modal-product-image">
                <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.title)}">
                ${stockBadge}
            </div>
            
            <div class="modal-product-info">
                <div style="color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">
                    ${product.category}
                </div>
                
                <h2 style="margin: 0 0 15px 0; font-size: 2rem; color: #1a4d2e;">${escapeHtml(product.title)}</h2>
                
                <div style="font-size: 2rem; font-weight: 700; color: #1a4d2e; margin-bottom: 20px;">
                    ${product.price.toFixed(2)} ${product.text.currency}
                </div>
                
                <div class="eco-dashboard ${product.eco_badge_class}">
                    <div class="eco-header">
                        <span><i class="fas fa-leaf" style="margin-right:5px;"></i> ${product.text.eco_score_label}</span>
                        <span>${product.eco_score}/10</span>
                    </div>
                    <div class="eco-bar-bg"><div class="eco-bar-fill" style="width: ${product.eco_width}%;"></div></div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <small style="color:#64748b; font-weight: 500;">${product.eco_text}</small>
                        <div class="co2-pill">
                            <i class="fas fa-cloud"></i> -${product.co2_saved}kg CO₂
                        </div>
                    </div>
                </div>
                
                ${sellerHTML}
                
                <p style="color: #4b5563; line-height: 1.8; margin: 20px 0;">${escapeHtml(product.description)}</p>
                
                ${stockInfo}
                
                <div style="margin-top: 25px;">
                    ${actionButton}
                </div>
            </div>
        </div>
        
        <div class="modal-reviews-section" style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb;">
            <h3 style="font-size: 1.5rem; margin-bottom: 20px; color: #1a4d2e;">
                <i class="fas fa-star" style="color: #fbbf24; margin-right: 8px;"></i>
                ${product.text.reviews_title}
            </h3>
            ${reviewsHTML}
        </div>
    `;
}

// Handle cart form submission in modal
function handleModalCartSubmit(event, productId, productTitle) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch('cart_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        // Show success toast
        showCartToast('added', `${productTitle} added to your basket`);
        
        // Update cart count
        updateCartCount();
        
        // Close modal after short delay
        setTimeout(() => {
            closeProductModal();
        }, 800);
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showCartToast('error', 'Failed to add item to cart');
    });
    
    return false;
}

// Show cart notification toast
function showCartToast(type, message) {
    // Remove existing toasts
    const existingToast = document.querySelector('.cart-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toastClass = type === 'removed' || type === 'error' ? 'toast-danger' : 'toast-success';
    const icon = type === 'removed' || type === 'error' ? 'fa-minus-circle' : 'fa-check-circle';
    const title = type === 'removed' ? 'Item removed' : (type === 'error' ? 'Error' : 'Item added');
    
    const toast = document.createElement('div');
    toast.className = `cart-toast ${toastClass} show`;
    toast.innerHTML = `
        <div class="toast-icon" aria-hidden="true">
            <i class="fas ${icon}"></i>
        </div>
        <div class="toast-copy">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${escapeHtml(message)}</div>
        </div>
        <button class="toast-close" type="button" aria-label="Close notification" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Create toast container if it doesn't exist
    let toastStack = document.querySelector('.toast-stack');
    if (!toastStack) {
        toastStack = document.createElement('div');
        toastStack.className = 'toast-stack';
        document.body.appendChild(toastStack);
    }
    
    toastStack.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Update cart count in navbar
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCountEl = document.getElementById('basket-count');
            if (cartCountEl) {
                cartCountEl.textContent = data.count || 0;
                cartCountEl.setAttribute('data-count', data.count || 0);
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeProductModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeProductModal();
    }
});

// Handle quick add to cart from product cards
function handleQuickAddToCart(event, productId, productTitle) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch('cart_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        // Show success toast
        showCartToast('added', `${productTitle} added to your basket`);
        
        // Update cart count
        updateCartCount();
        
        // Visual feedback on button
        const btn = form.querySelector('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '✓';
        btn.style.background = '#22c55e';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = '';
        }, 1000);
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showCartToast('error', 'Failed to add item to cart');
    });
    
    return false;
}
