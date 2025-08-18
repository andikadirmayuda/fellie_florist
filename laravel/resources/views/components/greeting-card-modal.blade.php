<!-- Greeting Card Modal Component -->
<style>
    .greeting-modal-scroll {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb #f3f4f6;
    }
    
    .greeting-modal-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .greeting-modal-scroll::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 3px;
    }
    
    .greeting-modal-scroll::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 3px;
    }
    
    .greeting-modal-scroll::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
    
    @media (max-height: 600px) {
        .greeting-modal-compact {
            max-height: calc(100vh - 1rem) !important;
        }
        
        .greeting-modal-compact .modal-body {
            max-height: calc(100vh - 120px) !important;
        }
        
        .greeting-modal-compact .components-section {
            max-height: 80px !important;
        }
    }
    
    /* Styling for out of stock components */
    .component-out-of-stock {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%) !important;
        border-color: #fecaca !important;
        position: relative;
    }
    
    .component-out-of-stock::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 2px,
            rgba(239, 68, 68, 0.1) 2px,
            rgba(239, 68, 68, 0.1) 4px
        );
        pointer-events: none;
        border-radius: 0.5rem;
    }
    
    /* Button state transitions */
    .add-to-cart-button {
        transition: all 0.3s ease;
    }
    
    .add-to-cart-button:disabled {
        transform: none !important;
        box-shadow: none !important;
    }
    
    .add-to-cart-button:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>

<div id="greetingCardModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all max-h-[90vh] flex flex-col greeting-modal-compact" style="max-height: calc(100vh - 2rem);">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center">
                    <i class="bi bi-card-text text-white text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Kartu Ucapan</h3>
                    <p class="text-xs sm:text-sm text-gray-500">Tambahkan pesan spesial untuk bouquet Anda</p>
                </div>
            </div>
            <button onclick="closeGreetingCardModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x-lg text-lg sm:text-xl"></i>
            </button>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1 greeting-modal-scroll modal-body" style="max-height: calc(90vh - 140px);">
            <div class="mb-3 sm:mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-flower1 mr-1 text-rose-500"></i>
                    Bouquet: <span id="modalBouquetName" class="font-semibold text-rose-600"></span>
                </label>
                <div class="text-xs text-gray-500 bg-rose-50 p-2 rounded-lg">
                    <span id="modalBouquetSize" class="font-medium"></span> -
                    Rp <span id="modalBouquetPrice" class="font-medium"></span>
                </div>
            </div>

            <!-- Komponen Bouquet Section -->
            <div class="mb-3 sm:mb-4" id="bouquetComponentsSection" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-list-ul mr-1 text-blue-500"></i>
                    Komponen Bouquet
                </label>
                <div class="bg-blue-50 p-2 sm:p-3 rounded-lg greeting-modal-scroll components-section" style="max-height: 120px; overflow-y: auto;">
                    <div id="bouquetComponentsList" class="space-y-1.5 sm:space-y-2">
                        <!-- Components will be loaded here -->
                    </div>
                    <div id="componentsLoading" class="text-center py-2">
                        <div class="inline-flex items-center text-xs sm:text-sm text-blue-600">
                            <i class="bi bi-arrow-clockwise animate-spin mr-2"></i>
                            Memuat komponen...
                        </div>
                    </div>
                    <div id="noComponentsMessage" class="text-center py-2 text-xs sm:text-sm text-gray-500" style="display: none;">
                        <i class="bi bi-info-circle mr-1"></i>
                        Tidak ada komponen tersedia untuk ukuran ini
                    </div>
                </div>
            </div>

            <div class="mb-3 sm:mb-4">
                <label for="greetingCardMessage" class="block text-sm font-medium text-gray-700 mb-2">
                    💌 Pesan Kartu Ucapan <span class="text-gray-400">(Opsional)</span>
                </label>
                <textarea id="greetingCardMessage" rows="3" maxlength="200"
                    class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition-all resize-none text-sm"
                    placeholder="Tulis pesan khusus Anda di sini... &#10;Contoh: &#10;• Happy Anniversary! ❤️&#10;• Selamat Ulang Tahun!&#10;• Semoga cepat sembuh 🌸"></textarea>
                <div class="flex justify-between items-center mt-2">
                    <div class="text-xs text-gray-500">
                        <i class="bi bi-info-circle mr-1"></i>
                        Pesan akan ditulis pada kartu cantik
                    </div>
                    <div class="text-xs text-gray-400">
                        <span id="characterCount">0</span>/200 karakter
                    </div>
                </div>
            </div>

            <!-- Quick Templates -->
            <div class="mb-4 sm:mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ✨ Template Cepat
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                        onclick="setGreetingTemplate('Happy Anniversary! Wishing you both all the happiness in the world. ❤️')"
                        class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 px-2 sm:px-3 py-2 rounded-lg transition-colors">
                        💕 Anniversary
                    </button>
                    <button type="button"
                        onclick="setGreetingTemplate('Selamat Ulang Tahun! Semoga semua impian Anda terwujud. 🎉')"
                        class="text-xs bg-pink-50 hover:bg-pink-100 text-pink-700 px-2 sm:px-3 py-2 rounded-lg transition-colors">
                        🎂 Ulang Tahun
                    </button>
                    <button type="button"
                        onclick="setGreetingTemplate('Semoga lekas sembuh dan sehat selalu. Get well soon! 🌸')"
                        class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-2 sm:px-3 py-2 rounded-lg transition-colors">
                        🌸 Get Well
                    </button>
                    <button type="button"
                        onclick="setGreetingTemplate('Congratulations! Wishing you success and happiness ahead. ✨')"
                        class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2 sm:px-3 py-2 rounded-lg transition-colors">
                        🎉 Congratulations
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-3 p-4 sm:p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex-shrink-0">
            <button onclick="closeGreetingCardModal()"
                class="flex-1 px-3 sm:px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors font-medium text-sm">
                <i class="bi bi-x-circle mr-2"></i>Batal
            </button>
            <div class="flex-1 relative">
                <button id="addToCartButton" onclick="addBouquetWithGreeting()"
                    class="w-full px-3 sm:px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white rounded-xl transition-all shadow-md hover:shadow-lg font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400 disabled:hover:bg-gray-400 add-to-cart-button"
                    disabled>
                    <i class="bi bi-cart-plus mr-2"></i>
                    <span id="addToCartText">Memeriksa Stok...</span>
                </button>
                <!-- Tooltip for disabled state -->
                <div id="buttonTooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-10" style="display: none;">
                    <span id="tooltipText">Memeriksa ketersediaan stok...</span>
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentGreetingData = null;

    // Show greeting card modal
    function showGreetingCardModal(bouquetId, bouquetName, sizeId, sizeName, price) {
        currentGreetingData = {
            bouquetId,
            bouquetName,
            sizeId,
            sizeName,
            price
        };

        // Update modal content
        document.getElementById('modalBouquetName').textContent = bouquetName;
        document.getElementById('modalBouquetSize').textContent = sizeName;
        document.getElementById('modalBouquetPrice').textContent = new Intl.NumberFormat('id-ID').format(price);

        // Clear previous message
        document.getElementById('greetingCardMessage').value = '';
        updateCharacterCount();

        // Load bouquet components
        loadBouquetComponents(bouquetId, sizeId);

        // Show modal
        document.getElementById('greetingCardModal').classList.remove('hidden');

        // Focus on textarea
        setTimeout(() => {
            document.getElementById('greetingCardMessage').focus();
        }, 100);
    }

    // Load bouquet components for specific size
    function loadBouquetComponents(bouquetId, sizeId) {
        // Show components section and loading
        document.getElementById('bouquetComponentsSection').style.display = 'block';
        document.getElementById('componentsLoading').style.display = 'block';
        document.getElementById('bouquetComponentsList').innerHTML = '';
        document.getElementById('noComponentsMessage').style.display = 'none';

        // Update button state to loading
        updateAddToCartButtonState('loading');

        // Fetch components from API
        fetch(`/bouquet/${bouquetId}/components/${sizeId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('componentsLoading').style.display = 'none';
                
                if (data.success && data.components && data.components.length > 0) {
                    displayBouquetComponents(data.components);
                } else {
                    document.getElementById('noComponentsMessage').style.display = 'block';
                    updateAddToCartButtonState('no-components');
                }
            })
            .catch(error => {
                console.error('Error loading bouquet components:', error);
                document.getElementById('componentsLoading').style.display = 'none';
                document.getElementById('noComponentsMessage').style.display = 'block';
                document.getElementById('noComponentsMessage').innerHTML = `
                    <i class="bi bi-exclamation-triangle mr-1"></i>
                    Gagal memuat komponen bouquet
                `;
                updateAddToCartButtonState('error');
            });
    }

    // Display bouquet components in the modal
    function displayBouquetComponents(components) {
        const componentsList = document.getElementById('bouquetComponentsList');
        
        // Check if all components have sufficient stock
        let allComponentsInStock = true;
        let outOfStockComponents = [];
        
        const componentsHtml = components.map(component => {
            const hasStock = component.current_stock > 0;
            const stockStatus = hasStock ? 
                `<span class="text-green-600 font-medium text-xs">Stok: ${component.current_stock} ${component.unit}</span>` :
                `<span class="text-red-600 font-medium text-xs">Stok Habis</span>`;
            
            // Track out of stock components
            if (!hasStock) {
                allComponentsInStock = false;
                outOfStockComponents.push(component.product_name);
            }
            
            return `
                <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-blue-100 ${!hasStock ? 'component-out-of-stock' : ''}">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-xs sm:text-sm text-gray-800 truncate">${component.product_name}</div>
                        <div class="text-xs text-gray-500">${component.product_category}</div>
                        <div class="text-xs text-blue-600">Jumlah: ${component.quantity} ${component.unit}</div>
                    </div>
                    <div class="text-right ml-2 flex-shrink-0">
                        <div class="text-xs text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(component.price)}/${component.unit}</div>
                        <div class="text-xs ${hasStock ? 'text-green-600' : 'text-red-600'}">${stockStatus}</div>
                    </div>
                </div>
            `;
        }).join('');

        componentsList.innerHTML = componentsHtml;
        
        // Update button state based on stock availability
        if (allComponentsInStock) {
            updateAddToCartButtonState('available');
        } else {
            updateAddToCartButtonState('out-of-stock', outOfStockComponents);
        }
    }

    // Update add to cart button state
    function updateAddToCartButtonState(state, outOfStockItems = []) {
        const button = document.getElementById('addToCartButton');
        const buttonText = document.getElementById('addToCartText');
        const tooltip = document.getElementById('buttonTooltip');
        const tooltipText = document.getElementById('tooltipText');
        
        switch (state) {
            case 'loading':
                button.disabled = true;
                buttonText.textContent = 'Memeriksa Stok...';
                tooltip.style.display = 'block';
                tooltip.style.opacity = '0';
                tooltipText.textContent = 'Memeriksa ketersediaan stok...';
                button.className = button.className.replace(/bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600/, 'bg-gray-400');
                break;
                
            case 'available':
                button.disabled = false;
                buttonText.textContent = 'Tambah ke Keranjang';
                tooltip.style.display = 'none';
                button.className = button.className.replace(/bg-gray-400/, 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600');
                break;
                
            case 'out-of-stock':
                button.disabled = true;
                if (outOfStockItems.length === 1) {
                    buttonText.textContent = `Stok ${outOfStockItems[0]} Habis`;
                    tooltip.style.display = 'block';
                    tooltip.style.opacity = '0';
                    tooltipText.textContent = `Stok ${outOfStockItems[0]} Habis`;
                } else if (outOfStockItems.length > 1) {
                    buttonText.textContent = `${outOfStockItems.length} Item Stok Habis`;
                    tooltip.style.display = 'block';
                    tooltip.style.opacity = '0';
                    tooltipText.textContent = `${outOfStockItems.length} Item Stok Habis`;
                } else {
                    buttonText.textContent = 'Stok Tidak Tersedia';
                    tooltip.style.display = 'block';
                    tooltip.style.opacity = '0';
                    tooltipText.textContent = 'Stok Tidak Tersedia';
                }
                button.className = button.className.replace(/bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600/, 'bg-gray-400');
                break;
                
            case 'no-components':
                button.disabled = true;
                buttonText.textContent = 'Tidak Ada Komponen';
                tooltip.style.display = 'block';
                tooltip.style.opacity = '0';
                tooltipText.textContent = 'Tidak Ada Komponen';
                button.className = button.className.replace(/bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600/, 'bg-gray-400');
                break;
                
            case 'error':
                button.disabled = true;
                buttonText.textContent = 'Error Memuat Data';
                tooltip.style.display = 'block';
                tooltip.style.opacity = '0';
                tooltipText.textContent = 'Error Memuat Data';
                button.className = button.className.replace(/bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600/, 'bg-gray-400');
                break;
        }
    }

    // Close greeting card modal
    function closeGreetingCardModal() {
        document.getElementById('greetingCardModal').classList.add('hidden');
        // Hide components section
        document.getElementById('bouquetComponentsSection').style.display = 'none';
        // Reset button state
        updateAddToCartButtonState('loading');
        currentGreetingData = null;
    }

    // Set greeting template
    function setGreetingTemplate(template) {
        document.getElementById('greetingCardMessage').value = template;
        updateCharacterCount();
    }

    // Update character count
    function updateCharacterCount() {
        const textarea = document.getElementById('greetingCardMessage');
        const count = textarea.value.length;
        document.getElementById('characterCount').textContent = count;

        // Change color based on length
        const countElement = document.getElementById('characterCount');
        if (count > 180) {
            countElement.className = 'text-red-500 font-medium';
        } else if (count > 150) {
            countElement.className = 'text-orange-500';
        } else {
            countElement.className = 'text-gray-400';
        }
    }

    // Add bouquet with greeting to cart
    function addBouquetWithGreeting() {
        if (!currentGreetingData) return;

        const button = document.getElementById('addToCartButton');
        if (button.disabled) return; // Prevent action if button is disabled

        const greetingMessage = document.getElementById('greetingCardMessage').value.trim();

        // Show loading state
        updateAddToCartButtonState('loading');

        // Add to cart via AJAX
        fetch('/cart/add-bouquet', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                bouquet_id: currentGreetingData.bouquetId,
                size_id: currentGreetingData.sizeId,
                quantity: 1,
                greeting_card: greetingMessage
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    let message = `${currentGreetingData.bouquetName} (${currentGreetingData.sizeName}) berhasil ditambahkan ke keranjang!`;
                    if (greetingMessage) {
                        message += ` Dengan kartu ucapan: "${greetingMessage.substring(0, 30)}${greetingMessage.length > 30 ? '...' : ''}"`;
                    }

                    // Use existing toast notification system
                    if (typeof showToast === 'function') {
                        showToast(message, 'success');
                    } else {
                        // Fallback to simple alert if showToast is not available
                        alert(message);
                    }

                    // Update cart badge
                    if (typeof updateCart === 'function') {
                        updateCart();
                    }

                    // Close modal
                    closeGreetingCardModal();
                } else {
                    // Use toast for error message
                    if (typeof showToast === 'function') {
                        showToast('Gagal menambahkan ke keranjang: ' + (data.message || 'Unknown error'), 'error');
                    } else {
                        alert('Gagal menambahkan ke keranjang: ' + (data.message || 'Unknown error'));
                    }
                    
                    // Reset button to available state
                    updateAddToCartButtonState('available');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                // Use toast for error message
                if (typeof showToast === 'function') {
                    showToast('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
                } else {
                    alert('Terjadi kesalahan saat menambahkan ke keranjang');
                }
                
                // Reset button to available state
                updateAddToCartButtonState('available');
            });
    }

    // Character count event listener
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('greetingCardMessage');
        if (textarea) {
            textarea.addEventListener('input', updateCharacterCount);
        }

        // Add tooltip functionality for disabled button
        const addToCartButton = document.getElementById('addToCartButton');
        const tooltip = document.getElementById('buttonTooltip');
        
        if (addToCartButton && tooltip) {
            addToCartButton.addEventListener('mouseenter', function() {
                if (this.disabled && tooltip.style.display !== 'none') {
                    tooltip.style.opacity = '1';
                }
            });
            
            addToCartButton.addEventListener('mouseleave', function() {
                tooltip.style.opacity = '0';
            });
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeGreetingCardModal();
        }
    });

    // Close modal on outside click
    document.getElementById('greetingCardModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeGreetingCardModal();
        }
    });
</script>