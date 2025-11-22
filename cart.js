// cart.js - Pure JS, No Bootstrap
document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '{}');
    const tbody = document.getElementById('cart-body');
    const totalEl = document.getElementById('cart-total');

    updateCartCount();
    renderCart();

    // Add to cart buttons (on index.php)
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const title = btn.dataset.title;
            const price = parseFloat(btn.dataset.price);

            if (!cart[id]) cart[id] = { title, price, qty: 0 };
            cart[id].qty++;
            saveCart();
            updateCartCount();

            btn.textContent = 'Added ✓';
            btn.disabled = true;
            setTimeout(() => {
                btn.textContent = 'Add to Cart';
                btn.disabled = false;
            }, 1000);
        });
    });

    // Checkout button
    document.getElementById('checkout-btn')?.addEventListener('click', () => {
        if (Object.keys(cart).length === 0) {
            alert('Your cart is empty!');
            return;
        }

        const itemsList = Object.values(cart)
            .map(item => `• ${item.title} ×${item.qty} = ₺${(item.price * item.qty).toFixed(2)}`)
            .join('\n');

        const grandTotal = Object.values(cart)
            .reduce((sum, i) => sum + i.price * i.qty, 0)
            .toFixed(2);

        alert(
            `This is a demo site. No real payment system is integrated.\n\n` +
            `Items in your cart:\n${itemsList}\n\n` +
            `Total: ₺${grandTotal}`
        );
    });
});

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCartCount() {
    const totalItems = Object.values(JSON.parse(localStorage.getItem('cart') || '{}'))
        .reduce((sum, item) => sum + item.qty, 0);
    const countEl = document.getElementById('cart-count');
    if (countEl) countEl.textContent = totalItems;
}

function renderCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '{}');
    const tbody = document.getElementById('cart-body');
    const totalEl = document.getElementById('cart-total');
    const table = document.getElementById('cart-table');
    const summary = document.getElementById('cart-summary');
    const empty = document.getElementById('empty-cart');

    if (Object.keys(cart).length === 0) {
        if (table) table.style.display = 'none';
        if (summary) summary.style.display = 'none';
        if (empty) empty.style.display = 'block';
        return;
    }

    if (table) table.style.display = 'table';
    if (summary) summary.style.display = 'block';
    if (empty) empty.style.display = 'none';

    tbody.innerHTML = '';
    let total = 0;

    for (const id in cart) {
        const item = cart[id];
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="max-width: 200px;">${item.title}</td>
            <td style="text-align: right;">₺${item.price.toFixed(2)}</td>
            <td style="text-align: center;">
                <input type="number" min="1" value="${item.qty}" class="qty-input" data-id="${id}" style="width: 70px;">
            </td>
            <td style="text-align: right;">₺${(item.price * item.qty).toFixed(2)}</td>
            <td style="text-align: center;">
                <button class="remove-item" data-id="${id}" style="background:#ef4444;color:white;border:none;width:32px;height:32px;border-radius:50%;font-size:1.2rem;cursor:pointer;">×</button>
            </td>
        `;
        tbody.appendChild(tr);
        total += item.price * item.qty;
    }

    totalEl.textContent = total.toFixed(2);

    // Quantity change handler
    tbody.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', e => {
            const id = e.target.dataset.id;
            const qty = parseInt(e.target.value);
            if (qty > 0) {
                cart[id].qty = qty;
            } else {
                delete cart[id];
            }
            saveCart();
            renderCart();
            updateCartCount();
        });
    });

    // Remove item handler
    tbody.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', () => {
            delete cart[btn.dataset.id];
            saveCart();
            renderCart();
            updateCartCount();
        });
    });
}