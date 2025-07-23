// Script khusus untuk cart (keranjang belanja)
function toggleCart() {
    const cart = document.getElementById('sideCart');
    cart.classList.toggle('translate-x-full');
    let overlay = document.getElementById('cartOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'cartOverlay';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300';
        overlay.onclick = toggleCart;
        document.body.appendChild(overlay);
    }
    if (cart.classList.contains('translate-x-full')) {
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 300);
    } else {
        overlay.classList.remove('opacity-0');
    }
}

function updateCart() {
    fetch('/cart/items')
    .then(response => response.json())
    .then(data => {
        const cartItemsContainer = document.getElementById('cartItems');
        const cartBadge = document.getElementById('cartBadge');
        const cartTotal = document.getElementById('cartTotal');
        if (data.items.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <i class="bi bi-bag-x text-5xl mb-2"></i>
                    <p>Keranjang belanja kosong</p>
                </div>
            `;
            cartBadge.classList.add('hidden');
            cartTotal.textContent = 'Rp 0';
            return;
        }
        cartBadge.classList.remove('hidden');
        cartBadge.textContent = data.items.length;
        cartItemsContainer.innerHTML = data.items.map(item => `
            <div class="flex items-start space-x-4 mb-4 pb-4 border-b border-gray-100">
                <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-lg">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">${item.name}</h4>
                    <p class="text-sm text-gray-500">${item.quantity} x Rp ${item.price.toLocaleString()}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <button onclick="updateQuantity(${item.id}, -1)" class="text-gray-500 hover:text-rose-600">-</button>
                        <span class="text-sm font-medium">${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, 1)" class="text-gray-500 hover:text-rose-600">+</button>
                    </div>
                </div>
                <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `).join('');
        cartTotal.textContent = `Rp ${data.total.toLocaleString()}`;
    });
}

function updateQuantity(itemId, change) {
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity_change: change })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCart();
        }
    });
}

function removeFromCart(itemId) {
    fetch(`/cart/remove/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCart();
        }
    });
}
