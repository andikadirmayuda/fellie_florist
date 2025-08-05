<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Bouquet Builder | Fellie Florist</title>
    <link rel="icon" href="{{ asset(config('app.logo')) }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <!-- Notification Styles -->
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body,
        .font-sans {
            font-family: 'Figtree', sans-serif;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        /* Custom Bouquet Specific Styles */
        .product-card:hover {
            transform: translateY(-2px);
        }

        .category-tab.active {
            border-color: #f43f5e;
            color: #f43f5e;
            background-color: white;
        }

        #selectedItems:empty:before {
            content: '';
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="font-sans gradient-bg min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('logo-fellie-02.png') }}" alt="Logo"
                            class="brand-logo w-10 h-10 rounded-full">
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Fellie Florist</h1>
                            <p class="text-xs text-gray-500">Custom Bouquet Builder</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('public.flowers') }}"
                        class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700">
                        🌸 Bunga
                    </a>
                    <a href="{{ route('public.bouquets') }}"
                        class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700">
                        💐 Bouquet
                    </a>
                    <a href="{{ route('custom.bouquet.create') }}"
                        class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-rose-600">
                        🎨 Custom Bouquet
                        <div
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-rose-400 to-pink-500 rounded-full">
                        </div>
                    </a>
                </nav>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Track Order -->
                    <a href="{{ route('public.order.track') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Lacak Pesanan">
                        <i class="bi bi-truck text-xl"></i>
                    </a>

                    <!-- Cart -->
                    <button onclick="toggleCart()"
                        class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Keranjang Belanja">
                        <i class="bi bi-bag text-xl"></i>
                        <span id="cartBadge"
                            class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center hidden">0</span>
                    </button>

                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Login">
                        <i class="bi bi-person-circle text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="min-h-screen bg-gradient-to-br from-rose-50 to-pink-50">
        <!-- Page Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">🎨 Custom Bouquet Builder</h1>
                        <p class="text-sm text-gray-600 mt-1">Buat bouquet impian Anda dengan memilih bunga sesuai
                            keinginan</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Draft ID: #{{ $customBouquet->id }}</div>
                        <div class="text-lg font-semibold text-rose-600" id="totalPrice">
                            Rp {{ number_format($customBouquet->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Panel: Product Selection -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <!-- Category Tabs -->
                        <div class="border-b bg-gray-50">
                            <div class="flex overflow-x-auto">
                                <button
                                    class="category-tab px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-rose-500 text-rose-600 bg-white"
                                    data-category="">
                                    Semua Produk
                                </button>
                                @foreach($categories as $category)
                                    <button
                                        class="category-tab px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                        data-category="{{ $category->id }}">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="productsGrid">
                                @foreach($products as $product)
                                    <div class="product-card bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                                        data-category="{{ $product->category_id }}" data-product-id="{{ $product->id }}">

                                        <!-- Product Image -->
                                        <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-t-lg overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                    class="w-full h-32 object-cover">
                                            @else
                                                <div
                                                    class="w-full h-32 bg-gradient-to-br from-rose-100 to-pink-100 flex items-center justify-center">
                                                    <span class="text-4xl">🌸</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $product->name }}</h3>
                                            <p class="text-xs text-gray-500 mb-2">
                                                {{ $product->category->name ?? 'Uncategorized' }}</p>

                                            <!-- Stock Info -->
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                                    📦 {{ $product->current_stock }} {{ $product->base_unit }} tersedia
                                                </span>
                                            </div>

                                            <!-- Price Preview (show default price) -->
                                            @php
                                                $defaultPrice = $product->prices->where('is_default', true)->first() ?? $product->prices->first();
                                            @endphp
                                            @if($defaultPrice)
                                                <div class="text-sm">
                                                    <span class="text-rose-600 font-semibold">
                                                        Rp {{ number_format($defaultPrice->price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-gray-500 text-xs">
                                                        /{{ $defaultPrice->type === 'per_tangkai' ? 'tangkai' : $defaultPrice->type }}
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Add Button -->
                                            <button
                                                class="w-full mt-3 bg-rose-500 hover:bg-rose-600 text-white text-sm py-2 px-3 rounded-md transition-colors add-product-btn"
                                                data-product-id="{{ $product->id }}">
                                                + Tambah ke Bouquet
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($products->isEmpty())
                                <div class="text-center py-12">
                                    <div class="text-6xl mb-4">🌸</div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk tersedia</h3>
                                    <p class="text-gray-500">Silakan periksa kembali nanti atau hubungi admin</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Builder Cart -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-6">
                        <!-- Cart Header -->
                        <div class="bg-gradient-to-r from-rose-500 to-pink-500 text-white p-4">
                            <h2 class="text-lg font-semibold">🛒 Bouquet Builder</h2>
                            <p class="text-sm opacity-90">Komponen yang dipilih</p>
                        </div>

                        <!-- Selected Items -->
                        <div class="p-4">
                            <div id="selectedItems" class="space-y-3 mb-4">
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">🌹</div>
                                    <p class="text-sm">Belum ada komponen yang dipilih</p>
                                    <p class="text-xs text-gray-400 mt-1">Klik "Tambah ke Bouquet" pada produk di
                                        sebelah kiri</p>
                                </div>
                            </div>

                            <!-- Reference Upload -->
                            <div class="border-t pt-4 mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📸 Upload Referensi (Opsional)
                                </label>
                                <div class="relative">
                                    <input type="file" id="referenceImage" accept="image/*" class="hidden">
                                    <button type="button" id="uploadReferenceBtn"
                                        class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-rose-400 transition-colors">
                                        <div class="text-gray-400">
                                            <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span class="text-sm">Klik untuk upload gambar</span>
                                        </div>
                                    </button>
                                </div>
                                <div id="referencePreview" class="mt-2 hidden">
                                    <img id="referenceImagePreview" class="w-full h-32 object-cover rounded-lg"
                                        alt="Reference">
                                    <button type="button" id="removeReferenceBtn"
                                        class="mt-2 text-xs text-red-600 hover:text-red-800">
                                        🗑️ Hapus gambar
                                    </button>
                                </div>
                            </div>

                            <!-- Total & Actions -->
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-sm font-medium text-gray-700">Total Harga:</span>
                                    <span class="text-lg font-bold text-rose-600" id="builderTotalPrice">
                                        Rp {{ number_format($customBouquet->total_price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <button type="button" id="addToMainCartBtn"
                                    class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-medium py-3 px-4 rounded-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    disabled>
                                    🛒 Tambah ke Keranjang Utama
                                </button>

                                <div class="mt-3 text-center">
                                    <button type="button" id="clearBuilderBtn"
                                        class="text-sm text-gray-500 hover:text-gray-700">
                                        🗑️ Kosongkan Builder
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 text-white p-4 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold" id="modalProductName">Pilih Opsi Produk</h3>
                    <button type="button" class="text-white hover:text-gray-200" id="closeModalBtn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div id="modalProductDetails">
                    <!-- Product details will be loaded here -->
                </div>

                <!-- Price Options -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Harga:</label>
                    <div id="priceOptions" class="space-y-2">
                        <!-- Price options will be loaded here -->
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah:</label>
                    <div class="flex items-center space-x-3">
                        <button type="button" id="decreaseQty"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">-</button>
                        <input type="number" id="quantity" value="1" min="1"
                            class="w-20 text-center border border-gray-300 rounded-md py-1">
                        <button type="button" id="increaseQty"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">+</button>
                    </div>
                    <div id="stockWarning" class="text-xs text-amber-600 mt-1 hidden">
                        ⚠️ Stok terbatas
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3">
                    <button type="button" id="cancelModalBtn"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-md transition-colors">
                        Batal
                    </button>
                    <button type="button" id="addToBuilderBtn"
                        class="flex-1 bg-rose-500 hover:bg-rose-600 text-white py-2 px-4 rounded-md transition-colors">
                        Tambah ke Builder
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        // Global variables
        let currentCustomBouquetId = {{ $customBouquet->id }};
        let selectedProduct = null;
        let selectedPriceType = null;
        let currentStock = 0;

        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function () {
            initializeCustomBouquetBuilder();
        });

        function initializeCustomBouquetBuilder() {
            // Category filtering
            initializeCategoryTabs();

            // Product selection
            initializeProductSelection();

            // Modal functionality
            initializeModal();

            // Reference image upload
            initializeReferenceUpload();

            // Builder actions
            initializeBuilderActions();

            // Load existing items if any
            loadCustomBouquetDetails();
        }

        function initializeCategoryTabs() {
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    // Update active tab
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Filter products
                    const categoryId = this.dataset.category;
                    filterProducts(categoryId);
                });
            });
        }

        function filterProducts(categoryId) {
            document.querySelectorAll('.product-card').forEach(card => {
                if (!categoryId || card.dataset.category === categoryId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function initializeProductSelection() {
            document.querySelectorAll('.add-product-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const productId = this.dataset.productId;
                    openProductModal(productId);
                });
            });
        }

        function initializeModal() {
            const modal = document.getElementById('productModal');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelModalBtn');

            [closeBtn, cancelBtn].forEach(btn => {
                btn.addEventListener('click', closeProductModal);
            });

            // Quantity controls
            document.getElementById('decreaseQty').addEventListener('click', () => adjustQuantity(-1));
            document.getElementById('increaseQty').addEventListener('click', () => adjustQuantity(1));
            document.getElementById('quantity').addEventListener('input', validateQuantity);

            // Add to builder
            document.getElementById('addToBuilderBtn').addEventListener('click', addToBuilder);
        }

        function initializeReferenceUpload() {
            const uploadBtn = document.getElementById('uploadReferenceBtn');
            const fileInput = document.getElementById('referenceImage');
            const removeBtn = document.getElementById('removeReferenceBtn');

            uploadBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', uploadReferenceImage);
            removeBtn.addEventListener('click', removeReferenceImage);
        }

        function initializeBuilderActions() {
            document.getElementById('addToMainCartBtn').addEventListener('click', addToMainCart);
            document.getElementById('clearBuilderBtn').addEventListener('click', clearBuilder);
        }

        async function openProductModal(productId) {
            try {
                const response = await fetch(`/product/${productId}/details`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    selectedProduct = data.product;
                    populateModal(data.product);
                    document.getElementById('productModal').classList.remove('hidden');
                    document.getElementById('productModal').classList.add('flex');
                } else {
                    showNotification('Error loading product details', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error loading product details', 'error');
            }
        }

        function populateModal(product) {
            document.getElementById('modalProductName').textContent = product.name;
            currentStock = product.current_stock;

            // Product details
            const detailsHtml = `
        <div class="mb-4">
            <h4 class="font-medium text-gray-900">${product.name}</h4>
            <p class="text-sm text-gray-600">${product.description || ''}</p>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    📦 ${product.current_stock} ${product.base_unit} tersedia
                </span>
            </div>
        </div>
    `;
            document.getElementById('modalProductDetails').innerHTML = detailsHtml;

            // Price options
            const priceOptionsHtml = product.prices.map(price => `
        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
            <input type="radio" name="price_type" value="${price.type}" class="text-rose-500 focus:ring-rose-500" ${price.is_default ? 'checked' : ''}>
            <div class="ml-3 flex-1">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-900">${price.display_name}</span>
                    <span class="text-sm font-semibold text-rose-600">Rp ${price.price.toLocaleString('id-ID')}</span>
                </div>
                <div class="text-xs text-gray-500">Setara ${price.unit_equivalent} ${product.base_unit}</div>
            </div>
        </label>
    `).join('');

            document.getElementById('priceOptions').innerHTML = priceOptionsHtml;

            // Set default price type
            const defaultPrice = product.prices.find(p => p.is_default) || product.prices[0];
            selectedPriceType = defaultPrice.type;

            // Price selection listeners
            document.querySelectorAll('input[name="price_type"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    selectedPriceType = this.value;
                    validateQuantity();
                });
            });

            // Reset quantity
            document.getElementById('quantity').value = 1;
            validateQuantity();
        }

        function adjustQuantity(delta) {
            const qtyInput = document.getElementById('quantity');
            const newValue = Math.max(1, parseInt(qtyInput.value) + delta);
            qtyInput.value = newValue;
            validateQuantity();
        }

        function validateQuantity() {
            const qty = parseInt(document.getElementById('quantity').value) || 1;
            const price = selectedProduct.prices.find(p => p.type === selectedPriceType);
            const requiredStock = qty * (price ? price.unit_equivalent : 1);

            const stockWarning = document.getElementById('stockWarning');
            const addBtn = document.getElementById('addToBuilderBtn');

            if (requiredStock > currentStock) {
                stockWarning.textContent = `⚠️ Tidak cukup stok. Dibutuhkan ${requiredStock} ${selectedProduct.base_unit}, tersedia ${currentStock}`;
                stockWarning.classList.remove('hidden');
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                stockWarning.classList.add('hidden');
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productModal').classList.remove('flex');
            selectedProduct = null;
            selectedPriceType = null;
        }

        async function addToBuilder() {
            if (!selectedProduct || !selectedPriceType) return;

            const quantity = parseInt(document.getElementById('quantity').value) || 1;

            try {
                const response = await fetch('/custom-bouquet/add-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        custom_bouquet_id: currentCustomBouquetId,
                        product_id: selectedProduct.id,
                        price_type: selectedPriceType,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    closeProductModal();
                    loadCustomBouquetDetails();
                    updateTotalPrice(data.total_price);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error adding item to builder', 'error');
            }
        }

        async function loadCustomBouquetDetails() {
            try {
                const response = await fetch(`/custom-bouquet/${currentCustomBouquetId}/details`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    displaySelectedItems(data.custom_bouquet.items);
                    updateTotalPrice(data.custom_bouquet.total_price);

                    // Update reference image if exists
                    if (data.custom_bouquet.reference_image_url) {
                        displayReferenceImage(data.custom_bouquet.reference_image_url);
                    }
                }
            } catch (error) {
                console.error('Error loading custom bouquet details:', error);
            }
        }

        function displaySelectedItems(items) {
            const container = document.getElementById('selectedItems');
            const addToCartBtn = document.getElementById('addToMainCartBtn');

            if (items.length === 0) {
                container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-2">🌹</div>
                <p class="text-sm">Belum ada komponen yang dipilih</p>
                <p class="text-xs text-gray-400 mt-1">Klik "Tambah ke Bouquet" pada produk di sebelah kiri</p>
            </div>
        `;
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            const itemsHtml = items.map(item => `
        <div class="bg-gray-50 rounded-lg p-3 border" data-item-id="${item.id}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">${item.product_name}</h4>
                    <p class="text-xs text-gray-600">${item.price_type_display}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        <button class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded text-xs flex items-center justify-center" 
                                onclick="updateItemQuantity(${item.id}, ${item.quantity - 1})">-</button>
                        <span class="text-sm font-medium px-2">${item.quantity}</span>
                        <button class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded text-xs flex items-center justify-center" 
                                onclick="updateItemQuantity(${item.id}, ${item.quantity + 1})">+</button>
                    </div>
                </div>
                <div class="text-right ml-3">
                    <div class="text-sm font-semibold text-rose-600">Rp ${item.subtotal.toLocaleString('id-ID')}</div>
                    <button class="text-xs text-red-600 hover:text-red-800 mt-1" onclick="removeItem(${item.id})">
                        🗑️ Hapus
                    </button>
                </div>
            </div>
        </div>
    `).join('');

            container.innerHTML = itemsHtml;
            addToCartBtn.disabled = false;
            addToCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        async function updateItemQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;

            try {
                const response = await fetch('/custom-bouquet/update-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    loadCustomBouquetDetails();
                    updateTotalPrice(data.total_price);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating quantity', 'error');
            }
        }

        async function removeItem(itemId) {
            if (!confirm('Hapus item ini dari bouquet?')) return;

            try {
                const response = await fetch('/custom-bouquet/remove-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadCustomBouquetDetails();
                    updateTotalPrice(data.total_price);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error removing item', 'error');
            }
        }

        async function uploadReferenceImage() {
            const fileInput = document.getElementById('referenceImage');
            const file = fileInput.files[0];

            if (!file) return;

            const formData = new FormData();
            formData.append('custom_bouquet_id', currentCustomBouquetId);
            formData.append('reference_image', file);

            try {
                const response = await fetch('/custom-bouquet/upload-reference', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    displayReferenceImage(data.image_url);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error uploading image', 'error');
            }
        }

        function displayReferenceImage(imageUrl) {
            document.getElementById('referenceImagePreview').src = imageUrl;
            document.getElementById('referencePreview').classList.remove('hidden');
            document.getElementById('uploadReferenceBtn').classList.add('hidden');
        }

        function removeReferenceImage() {
            document.getElementById('referencePreview').classList.add('hidden');
            document.getElementById('uploadReferenceBtn').classList.remove('hidden');
            document.getElementById('referenceImage').value = '';
        }

        function updateTotalPrice(totalPrice) {
            document.getElementById('totalPrice').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById('builderTotalPrice').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
        }

        async function addToMainCart() {
            // Check if custom bouquet has items
            const selectedItems = document.querySelectorAll('#selectedItems .bg-gray-50');
            if (selectedItems.length === 0) {
                showNotification('Bouquet masih kosong. Tambahkan beberapa bunga terlebih dahulu.', 'error');
                return;
            }

            try {
                // Set status to finalized first
                const finalizeResponse = await fetch(`/custom-bouquet/${currentCustomBouquetId}/finalize`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    }
                });

                const finalizeData = await finalizeResponse.json();
                if (!finalizeData.success) {
                    throw new Error(finalizeData.message || 'Gagal memfinalisasi bouquet');
                }

                // Now add to cart
                const cartResponse = await fetch('/cart/add-custom-bouquet', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    },
                    body: JSON.stringify({
                        custom_bouquet_id: currentCustomBouquetId,
                        quantity: 1
                    })
                });

                const cartData = await cartResponse.json();
                if (cartData.success) {
                    showNotification('Custom bouquet berhasil ditambahkan ke keranjang!', 'success');
                    updateCart(); // Update cart display

                    // Optionally redirect to main flowers page after success
                    setTimeout(() => {
                        window.location.href = '/product-fellie';
                    }, 2000);
                } else {
                    throw new Error(cartData.message || 'Gagal menambahkan ke keranjang');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        async function clearBuilder() {
            if (!confirm('Kosongkan semua item dari builder?')) return;

            // This will be implemented - remove all items from the custom bouquet
            showNotification('Builder dikosongkan (coming soon)', 'info');
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${getNotificationColor(type)}`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function getNotificationColor(type) {
            switch (type) {
                case 'success': return 'bg-green-500 text-white';
                case 'error': return 'bg-red-500 text-white';
                case 'warning': return 'bg-yellow-500 text-white';
                default: return 'bg-blue-500 text-white';
            }
        }

        function addCustomBouquetToCart() {
            // Check if custom bouquet has items
            const selectedItems = document.querySelectorAll('#selectedItems .bg-white');
            if (selectedItems.length === 0) {
                showNotification('Bouquet masih kosong. Tambahkan beberapa bunga terlebih dahulu.', 'error');
                return;
            }

            // Set status to finalized first
            fetch(`/custom-bouquet/${currentCustomBouquetId}/finalize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Now add to cart
                        return fetch('/cart/add-custom-bouquet', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken
                            },
                            body: JSON.stringify({
                                custom_bouquet_id: currentCustomBouquetId,
                                quantity: 1
                            })
                        });
                    } else {
                        throw new Error(data.message || 'Gagal memfinalisasi bouquet');
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Custom bouquet berhasil ditambahkan ke keranjang!', 'success');
                        updateCart(); // Update cart display

                        // Optionally redirect to main cart or flowers page
                        setTimeout(() => {
                            window.location.href = '/product-fellie';
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    showNotification('Terjadi kesalahan: ' + error.message, 'error');
                });
        }
    </script>

    <!-- Include Cart Modal -->
    @include('public.partials.cart-modal')

    <!-- Include Cart Panel -->
    @include('public.partials.cart-panel')

    <!-- Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>

</body>

</html>