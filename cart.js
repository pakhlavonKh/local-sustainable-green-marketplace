// cart.js - Pure JS, No Bootstrap
document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '{}');
    const tbody = document.getElementById('cart-body');
    const totalEl = document.getElementById('cart-total');
    const table = document.getElementById('cart-table');
    const summary = document.getElementById('cart-summary');
    const empty = document.getElementById('empty-cart');

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
            btn.textContent = 'Eklendi ✓';
            btn.disabled = true;
            setTimeout(() => {
                btn.textContent = 'Sepete Ekle';
                btn.disabled = false;
            }, 1000);
        });
    });

    // Checkout button
    document.getElementById('checkout-btn')?.addEventListener('click', () => {
        if (Object.keys(cart).length === 0) {
            alert('Sepetiniz boş!');
            return;
        }
        alert('Bu bir demo sitesidir. Gerçek ödeme sistemi entegre edilmemiştir.\n\nSepetinizdeki ürünler:\n' +
              Object.values(cart).map(item => `• ${item.title} x${item.qty} = ₺${(item.price * item.qty).toFixed(2)}`).join('\n') +
              `\n\nToplam: ₺${Object.values(cart).reduce((sum, i) => sum + i.price * i.qty, 0).toFixed(2)}`
        );
    });
});

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCartCount() {
    const total = Object.values(JSON.parse(localStorage.getItem('cart') || '{}'))
        .reduce((sum, item) => sum + item.qty, 0);
    const countEl = document.getElementById('cart-count');
    if (countEl) countEl.textContent = total;
}

function renderCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '{}');
    const tbody = document.getElementById('cart-body');
    const totalEl = document.getElementById('cart-total');
    const table = document.getElementById('cart-table');
    const summary = document.getElementById('cart-summary');
    const empty = document.getElementById('empty-cart');

    if (Object.keys(cart).length === 0) {
        table.style.display = 'none';
        summary.style.display = 'none';
        empty.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    summary.style.display = 'block';
    empty.style.display = 'none';

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

    // Quantity change
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

    // Remove item
    tbody.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', e => {
            delete cart[e.target.dataset.id];
            saveCart();
            renderCart();
            updateCartCount();
        });
    });
}