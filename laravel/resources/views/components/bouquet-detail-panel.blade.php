<!-- Bouquet Detail Panel -->
<div id="bouquetDetailPanel"
    class="fixed top-0 right-0 h-full w-full md:w-96 lg:w-[480px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">

    <!-- Panel Header -->
    <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between z-10">
        <h2 class="text-xl font-bold text-gray-800">Detail Bouquet</h2>
        <button onclick="closeBouquetDetailPanel()" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
            <i class="bi bi-x-lg text-xl text-gray-600"></i>
        </button>
    </div>

    <!-- Panel Content -->
    <div class="overflow-y-auto h-full pb-20">
        <div id="bouquetDetailContent" class="p-6">
            <!-- Content will be populated by JavaScript -->
            <div class="flex items-center justify-center h-64">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-rose-500 mx-auto mb-4"></div>
                    <p class="text-gray-500">Memuat detail bouquet...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Footer Actions -->
    <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
        <div class="flex gap-3">
            <button id="addBouquetToCart" onclick="addCurrentBouquetToCart()"
                class="flex-1 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="bi bi-cart-plus mr-2"></i>
                <span id="addToCartText">Tambah ke Keranjang</span>
            </button>
            <button onclick="closeBouquetDetailPanel()"
                class="px-6 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold rounded-xl transition-all duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="bouquetDetailOverlay"
    class="fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300 z-40">
</div>

<script>
    let currentBouquetDetail = null;

    function showBouquetDetailPanel(bouquetId) {
        console.log('Loading bouquet detail for ID:', bouquetId);

        // Show panel and overlay
        const panel = document.getElementById('bouquetDetailPanel');
        const overlay = document.getElementById('bouquetDetailOverlay');

        overlay.classList.remove('pointer-events-none');
        overlay.classList.add('opacity-100');

        // Slide in panel
        setTimeout(() => {
            panel.classList.remove('translate-x-full');
        }, 10);

        // Load bouquet data
        loadBouquetDetail(bouquetId);
    }

    function closeBouquetDetailPanel() {
        const panel = document.getElementById('bouquetDetailPanel');
        const overlay = document.getElementById('bouquetDetailOverlay');

        // Slide out panel
        panel.classList.add('translate-x-full');

        // Hide overlay
        setTimeout(() => {
            overlay.classList.remove('opacity-100');
            overlay.classList.add('pointer-events-none');
        }, 300);

        currentBouquetDetail = null;
    }

    function loadBouquetDetail(bouquetId) {
        // Show loading state
        const content = document.getElementById('bouquetDetailContent');
        content.innerHTML = `
        <div class="flex items-center justify-center h-64">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-rose-500 mx-auto mb-4"></div>
                <p class="text-gray-500">Memuat detail bouquet...</p>
            </div>
        </div>
    `;

        // Fetch bouquet detail via AJAX
        fetch(`/bouquet/${bouquetId}/detail-json`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch bouquet detail');
                }
                return response.json();
            })
            .then(bouquet => {
                currentBouquetDetail = bouquet;
                renderBouquetDetail(bouquet);
            })
            .catch(error => {
                console.error('Error loading bouquet detail:', error);
                content.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Gagal Memuat Detail</h3>
                    <p class="text-gray-600 mb-4">Terjadi kesalahan saat memuat detail bouquet.</p>
                    <button onclick="loadBouquetDetail(${bouquetId})" 
                        class="px-4 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors">
                        Coba Lagi
                    </button>
                </div>
            `;
            });
    }

    function renderBouquetDetail(bouquet) {
        const content = document.getElementById('bouquetDetailContent');

        // Calculate price range
        const prices = bouquet.prices || [];
        const minPrice = prices.length > 0 ? Math.min(...prices.map(p => p.price)) : 0;
        const maxPrice = prices.length > 0 ? Math.max(...prices.map(p => p.price)) : 0;

        content.innerHTML = `
        <!-- Bouquet Image -->
        <div class="relative h-64 mb-6 rounded-xl overflow-hidden">
            ${bouquet.image ?
                `<img src="/storage/${bouquet.image}" alt="${bouquet.name}" class="w-full h-full object-cover">` :
                `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100">
                    <i class="bi bi-flower3 text-4xl text-rose-400"></i>
                </div>`
            }
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4">
                <h1 class="text-2xl font-bold text-white mb-2">${bouquet.name}</h1>
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm">
                    ${bouquet.category?.name || 'Bouquet'}
                </span>
            </div>
        </div>

        <!-- Price Range -->
        <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl p-4 mb-6">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Rentang Harga</p>
                <div class="text-2xl font-bold text-rose-600">
                    ${minPrice === maxPrice ?
                `Rp ${new Intl.NumberFormat('id-ID').format(minPrice)}` :
                `Rp ${new Intl.NumberFormat('id-ID').format(minPrice)} - ${new Intl.NumberFormat('id-ID').format(maxPrice)}`
            }
                </div>
            </div>
        </div>

        <!-- Description -->
        ${bouquet.description ? `
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi</h3>
                <p class="text-gray-600 leading-relaxed">${bouquet.description}</p>
            </div>
        ` : ''}

        <!-- Available Sizes & Prices -->
        <div class="mb-6" data-section="sizes">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ukuran & Harga Tersedia</h3>
            <div class="space-y-3">
                ${prices.map(price => `
                    <div class="group border-2 border-gray-200 rounded-xl p-4 hover:border-rose-300 hover:bg-rose-50 transition-all duration-200 cursor-pointer"
                         onmouseenter="this.classList.add('shadow-md')" 
                         onmouseleave="this.classList.remove('shadow-md')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">${price.size?.name || 'Standard'}</div>
                                <div class="text-sm text-gray-600">Ukuran ${(price.size?.name || 'standard').toLowerCase()}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-rose-600">Rp ${new Intl.NumberFormat('id-ID').format(price.price)}</div>
                                <div class="text-xs text-gray-500">per bouquet</div>
                            </div>
                        </div>
                        <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="selectBouquetSize(${price.id}, ${price.size_id}, '${price.size?.name || 'Standard'}', ${price.price})"
                                class="w-full bg-gradient-to-r from-rose-500 to-pink-500 text-white py-2 px-4 rounded-lg text-sm font-medium hover:from-rose-600 hover:to-pink-600 transition-all duration-200">
                                <i class="bi bi-cart-plus mr-2"></i>Pilih Ukuran Ini
                            </button>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>

        <!-- Components (if available) -->
        ${bouquet.components && bouquet.components.length > 0 ? `
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Komponen Bunga</h3>
                <div class="grid grid-cols-1 gap-3">
                    ${bouquet.components.map(component => `
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-flower1 text-rose-500"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800">${component.product?.name || 'Bunga'}</div>
                                <div class="text-sm text-gray-600">Jumlah: ${component.quantity || 1}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        ` : ''}

        <!-- Additional Info -->
        <div class="bg-blue-50 rounded-xl p-4">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <i class="bi bi-info-circle text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-blue-800 mb-2">Informasi Penting</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Harga sudah termasuk biaya arrangement dan kemasan</li>
                        <li>• Bunga segar dengan kualitas premium</li>
                        <li>• Dapat disesuaikan dengan kebutuhan khusus</li>
                        <li>• Pemesanan dapat dilakukan via WhatsApp</li>
                    </ul>
                </div>
            </div>
        </div>
    `;

        // Update footer button
        updateFooterButton(bouquet);
    }

    function updateFooterButton(bouquet) {
        const addToCartBtn = document.getElementById('addBouquetToCart');
        const addToCartText = document.getElementById('addToCartText');

        if (bouquet.prices && bouquet.prices.length === 1) {
            addToCartText.textContent = 'Tambah ke Keranjang';
            addToCartBtn.onclick = () => addCurrentBouquetToCart();
        } else if (bouquet.prices && bouquet.prices.length > 1) {
            addToCartText.textContent = 'Pilih Ukuran';
            addToCartBtn.onclick = () => {
                // Scroll to sizes section
                const sizesSection = document.querySelector('[data-section="sizes"]');
                if (sizesSection) {
                    sizesSection.scrollIntoView({ behavior: 'smooth' });
                }
            };
        } else {
            addToCartText.textContent = 'Hubungi Kami';
            addToCartBtn.onclick = () => {
                window.open('https://wa.me/6282177929879?text=Halo, saya tertarik dengan bouquet ' + encodeURIComponent(bouquet.name), '_blank');
            };
        }
    }

    let selectedBouquetSize = null;

    function selectBouquetSize(priceId, sizeId, sizeName, price) {
        selectedBouquetSize = { priceId, sizeId, sizeName, price };

        // Update footer button
        const addToCartText = document.getElementById('addToCartText');
        addToCartText.textContent = `Tambah ${sizeName} - Rp ${new Intl.NumberFormat('id-ID').format(price)}`;

        // Update all size cards to show selection
        document.querySelectorAll('[onmouseenter*="shadow-md"]').forEach(card => {
            card.classList.remove('border-rose-500', 'bg-rose-50');
            card.classList.add('border-gray-200');
        });

        // Highlight selected card
        event.target.closest('[onmouseenter*="shadow-md"]').classList.remove('border-gray-200');
        event.target.closest('[onmouseenter*="shadow-md"]').classList.add('border-rose-500', 'bg-rose-50');
    }

    function addCurrentBouquetToCart() {
        if (!currentBouquetDetail) {
            alert('Data bouquet tidak tersedia');
            return;
        }

        if (currentBouquetDetail.prices.length > 1 && !selectedBouquetSize) {
            alert('Silakan pilih ukuran terlebih dahulu');
            return;
        }

        const priceData = selectedBouquetSize || {
            priceId: currentBouquetDetail.prices[0].id,
            sizeId: currentBouquetDetail.prices[0].size_id,
            sizeName: currentBouquetDetail.prices[0].size?.name || 'Standard',
            price: currentBouquetDetail.prices[0].price
        };

        // Add to cart via AJAX
        fetch('/cart/add-bouquet', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                bouquet_id: currentBouquetDetail.id,
                size_id: priceData.sizeId || currentBouquetDetail.prices[0].size_id,
                quantity: 1
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const message = `${currentBouquetDetail.name} (${priceData.sizeName}) berhasil ditambahkan ke keranjang!`;
                    showSuccessNotification(message);

                    // Update cart badge
                    if (typeof updateCart === 'function') {
                        updateCart();
                    }

                    // Close panel
                    closeBouquetDetailPanel();
                } else {
                    alert('Gagal menambahkan ke keranjang: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                alert('Terjadi kesalahan saat menambahkan ke keranjang');
            });
    }

    function showSuccessNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
        <div class="flex items-center">
            <i class="bi bi-check-circle mr-2"></i>
            ${message}
        </div>
    `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Animate out and remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Close panel when clicking overlay
    document.getElementById('bouquetDetailOverlay').addEventListener('click', closeBouquetDetailPanel);

    // Close panel with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !document.getElementById('bouquetDetailPanel').classList.contains('translate-x-full')) {
            closeBouquetDetailPanel();
        }
    });
</script>