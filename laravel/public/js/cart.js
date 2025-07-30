// Script khusus untuk cart (keranjang belanja)
function formatPrice(price) {
    return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Initialize cart when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCart();
});

function toggleCart() {
    const cart = document.getElementById('sideCart');
    if (!cart) {
        console.error('Element sideCart tidak ditemukan');
        return;
    }
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
        // Update cart when opening
        updateCart();
    }
}

function updateCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartBadge = document.getElementById('cartBadge');
    const cartTotal = document.getElementById('cartTotal');
    const checkoutButton = document.querySelector('#sideCart a[href*="checkout"]');
    
    // Show loading state
    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = `
            <div class="flex items-center justify-center h-20">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-rose-500"></div>
                <span class="ml-2 text-gray-500">Memuat...</span>
            </div>
        `;
    }
    
    fetch('/cart/get')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data.success) {
            console.error('Cart update failed:', data.message);
            if (cartItemsContainer) {
                cartItemsContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-red-500">
                        <i class="bi bi-exclamation-triangle text-3xl mb-2"></i>
                        <p>Gagal memuat keranjang</p>
                    </div>
                `;
            }
            return;
        }
        
        if (data.items.length === 0) {
            cartItemsContainer.innerHTML = `
                <!-- Cart Information Panel -->
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-info-circle text-rose-600 mr-2"></i>
                        <h4 class="font-semibold text-rose-800 text-sm">Keranjang Terpadu</h4>
                    </div>
                    <div class="text-xs text-rose-700 space-y-1">
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bunga</span>
                            <span>Produk bunga satuan dengan berbagai pilihan harga</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bouquet</span>
                            <span>Rangkaian bunga siap jadi dengan berbagai ukuran</span>
                        </div>
                        <p class="text-rose-600 mt-2 text-xs italic">
                            💡 Anda dapat menambahkan bunga dan bouquet dalam satu keranjang yang sama!
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <i class="bi bi-bag-x text-5xl mb-2"></i>
                    <p>Keranjang belanja kosong</p>
                </div>
            `;
            if (cartBadge) cartBadge.classList.add('hidden');
            if (cartTotal) cartTotal.textContent = 'Rp 0';
            if (checkoutButton) checkoutButton.style.display = 'none';
            return;
        }
        
        if (cartBadge) {
            cartBadge.classList.remove('hidden');
            cartBadge.textContent = data.items.length;
        }
        if (checkoutButton) checkoutButton.style.display = 'block';
        
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = `
                <!-- Cart Information Panel -->
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-info-circle text-rose-600 mr-2"></i>
                        <h4 class="font-semibold text-rose-800 text-sm">Keranjang Terpadu</h4>
                    </div>
                    <div class="text-xs text-rose-700 space-y-1">
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bunga</span>
                            <span>Produk bunga satuan dengan berbagai pilihan harga</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">Bouquet</span>
                            <span>Rangkaian bunga siap jadi dengan berbagai ukuran</span>
                        </div>
                        <p class="text-rose-600 mt-2 text-xs italic">
                            💡 Anda dapat menambahkan bunga dan bouquet dalam satu keranjang yang sama!
                        </p>
                    </div>
                </div>
                ${data.items.map(item => `
                <div class="flex items-start space-x-4 mb-4 pb-4 border-b border-gray-100">
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                        ${item.image ? 
                            `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">` : 
                            `<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                            </svg>`
                        }
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm">${item.name}</h4>
                        ${item.type === 'bouquet' ? 
                            `<span class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs px-2 py-1 rounded-full mb-1">Bouquet</span>` : 
                            `<span class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white text-xs px-2 py-1 rounded-full mb-1">Bunga</span>`
                        }
                        <p class="text-sm text-gray-500">${item.quantity} x Rp ${formatPrice(item.price)}</p>
                        ${item.price_type ? `<p class="text-xs text-gray-400">${item.price_type}</p>` : ''}
                        ${item.greeting_card ? 
                            `<div class="mt-1 p-1.5 bg-pink-50 border border-pink-200 rounded text-xs">
                                <div class="flex items-center text-pink-700 mb-1">
                                    <i class="bi bi-card-text mr-1"></i>
                                    <span class="font-medium">Kartu Ucapan:</span>
                                </div>
                                <div class="text-pink-800 italic">"${item.greeting_card.length > 40 ? item.greeting_card.substring(0, 40) + '...' : item.greeting_card}"</div>
                            </div>` : ''
                        }
                        ${item.greeting_card && item.greeting_card.trim() ? 
                            `<div class="mt-2 p-2 bg-pink-50 border border-pink-200 rounded-lg">
                                <div class="flex items-center mb-1">
                                    <i class="bi bi-card-text text-pink-600 mr-1"></i>
                                    <span class="text-xs font-medium text-pink-700">Kartu Ucapan:</span>
                                </div>
                                <p class="text-xs text-pink-800 italic">"${item.greeting_card.length > 50 ? item.greeting_card.substring(0, 50) + '...' : item.greeting_card}"</p>
                            </div>` : ''
                        }
                        <div class="flex items-center space-x-2 mt-2">
                            <button onclick="updateQuantity('${item.id}', -1)" class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">-</button>
                            <span class="text-sm font-medium min-w-[20px] text-center">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.id}', 1)" class="w-6 h-6 bg-gray-100 hover:bg-rose-100 text-gray-600 hover:text-rose-600 rounded-full flex items-center justify-center transition-colors duration-200">+</button>
                        </div>
                    </div>
                    <button onclick="removeFromCart('${item.id}', '${item.name.replace(/'/g, "\\\'")}')" class="text-gray-400 hover:text-red-500 p-1 transition-colors duration-200 hover:bg-red-50 rounded-lg">
                        <i class="bi bi-trash text-lg"></i>
                    </button>
                </div>
            `).join('')}
            `;
        }
        
        if (cartTotal) {
            cartTotal.textContent = `Rp ${formatPrice(data.total)}`;
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-red-500">
                    <i class="bi bi-exclamation-triangle text-3xl mb-2"></i>
                    <p class="text-sm">Gagal memuat keranjang</p>
                    <button onclick="updateCart()" class="mt-2 text-xs text-rose-600 hover:underline">Coba lagi</button>
                </div>
            `;
        }
    });
}

function updateQuantity(cartKey, change) {
    // Disable buttons sementara untuk mencegah multiple clicks
    const buttons = document.querySelectorAll(`button[onclick*="updateQuantity('${cartKey}'"]`);
    buttons.forEach(btn => btn.disabled = true);
    
    fetch(`/cart/update/${cartKey}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity_change: change })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCart();
        } else {
            console.error('Update quantity failed:', data.message);
            alert('Gagal mengupdate jumlah: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        alert('Terjadi kesalahan saat mengupdate jumlah produk');
    })
    .finally(() => {
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
    });
}

function removeFromCart(cartKey, itemName = 'produk ini') {
    // Create custom confirmation modal
    const modal = document.createElement('div');
    modal.id = 'deleteConfirmModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-[100] flex items-center justify-center p-4';
    modal.style.opacity = '0';
    modal.style.transition = 'opacity 0.3s ease';
    
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform scale-95 transition-transform duration-300" id="modalContent">
            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus dari Keranjang?</h3>
                
                <!-- Message -->
                <p class="text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <span class="font-semibold text-gray-700">${itemName}</span> dari keranjang belanja?</p>
                
                <!-- Buttons -->
                <div class="flex space-x-3">
                    <button type="button" 
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors duration-200" 
                            onclick="closeDeleteModal()">
                        <i class="bi bi-x-circle mr-2"></i>Batal
                    </button>
                    <button type="button" 
                            class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl" 
                            onclick="confirmRemoveFromCart('${cartKey}')">
                        <i class="bi bi-trash3 mr-2"></i>Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Animate modal appearance
    requestAnimationFrame(() => {
        modal.style.opacity = '1';
        document.getElementById('modalContent').style.transform = 'scale(1)';
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeDeleteModal();
        }
    });
    
    return;
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.style.opacity = '0';
        document.getElementById('modalContent').style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

function confirmRemoveFromCart(cartKey) {
    closeDeleteModal();
    
    // Show loading toast
    showToast('Menghapus produk...', 'loading');
    
    fetch(`/cart/remove/${cartKey}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCart();
            showToast('Produk berhasil dihapus dari keranjang!', 'success');
        } else {
            console.error('Remove from cart failed:', data.message);
            showToast('Gagal menghapus produk: ' + (data.message || 'Terjadi kesalahan'), 'error');
        }
    })
    .catch(error => {
        console.error('Error removing from cart:', error);
        showToast('Terjadi kesalahan saat menghapus produk dari keranjang', 'error');
    });
}

function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.getElementById('cartToast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.id = 'cartToast';
    toast.className = 'fixed top-4 right-4 z-[110] transform translate-x-full transition-transform duration-300';
    
    const bgColors = {
        success: 'bg-gradient-to-r from-green-500 to-green-600',
        error: 'bg-gradient-to-r from-red-500 to-red-600',
        loading: 'bg-gradient-to-r from-blue-500 to-blue-600',
        info: 'bg-gradient-to-r from-gray-500 to-gray-600'
    };
    
    const icons = {
        success: '<i class="bi bi-check-circle-fill"></i>',
        error: '<i class="bi bi-x-circle-fill"></i>',
        loading: '<i class="bi bi-arrow-repeat animate-spin"></i>',
        info: '<i class="bi bi-info-circle-fill"></i>'
    };
    
    toast.innerHTML = `
        <div class="${bgColors[type]} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 min-w-[300px]">
            <span class="text-lg">${icons[type]}</span>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate toast appearance
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full');
    });
    
    // Auto remove toast (except for loading)
    if (type !== 'loading') {
        setTimeout(() => {
            if (toast && toast.parentNode) {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 3000);
    }
}
