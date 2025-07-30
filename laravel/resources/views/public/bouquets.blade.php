<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Bouquet Fellie Florist</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <!-- Notification Styles -->
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        /* Modal animations */
        .modal-enter {
            opacity: 0;
            transform: scale(0.9);
        }

        .modal-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: all 0.3s ease-out;
        }
    </style>
    <script>
        // Fungsi untuk menambah ke keranjang dengan pilihan harga (global)
        function addToCartWithPrice(flowerId, priceType) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: flowerId, price_type: priceType })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal menambah ke keranjang. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        closeCartPriceModal();
                        updateCart();
                        toggleCart();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambah ke keranjang: ' + error.message);
                });
        }
    </script>
</head>

<body class="font-sans bg-gradient-to-br from-rose-50 via-pink-50 to-orange-50 min-h-screen">
    @include('public.partials.cart-panel')

    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40 bg-white/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Top Bar -->
            <div class="flex items-center justify-between h-16">
                <!-- Brand Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center">
                            <img src="{{ asset('logo-fellie-02.png') }}" alt="Logo"
                                class="brand-logo rounded-full w-8 h-8">
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Fellie Florist</h1>
                            <p class="text-xs text-gray-500">Supplier Bunga</p>
                        </div>
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Track Order -->
                    <a href="{{ route('public.order.track') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Lacak Pesanan">
                        <i class="bi bi-truck text-xl"></i>
                    </a>

                    <!-- Order Detail - Muncul setelah checkout -->
                    @if(session('last_public_order_code'))
                        <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                            class="relative text-white bg-rose-500 hover:bg-rose-600 p-2 rounded-full hover:shadow-lg transition-all duration-200 order-detail-pulse"
                            title="Lihat Detail Pesanan Terbaru - Kode: {{ session('last_public_order_code') }}">
                            <i class="bi bi-receipt-cutoff text-xl"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold notification-badge">
                                ✓
                            </span>
                        </a>
                    @endif

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

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-rose-500 to-pink-600 rounded-full mb-6 shadow-lg">
                <i class="bi bi-flower3 text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Koleksi <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600">Bouquet</span> Kami
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Rangkaian bunga indah yang dirancang khusus untuk momen spesial Anda
            </p>
            <div class="flex items-center justify-center gap-2 mt-4 text-sm text-gray-500">
                <i class="bi bi-clock"></i>
                <span>Terakhir diperbarui:
                    {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('d M Y, H:i') : '-' }}</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex justify-center mb-8">
            <div class="flex bg-white rounded-2xl shadow-lg p-2 border border-rose-100">
                <a href="{{ route('public.flowers') }}"
                    class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700">
                    🌺 Bunga
                </a>
                <a href="{{ route('public.bouquets') }}"
                    class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-rose-600">
                    💐 Bouquet
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-500 to-pink-500 rounded-xl -z-10"></div>
                    <div class="absolute inset-0.5 bg-white rounded-xl -z-10"></div>
                </a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-rose-100 p-6 mb-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="searchInput"
                            class="w-full pl-12 pr-4 py-3 border border-rose-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-200"
                            placeholder="Cari bouquet impian Anda..." onkeyup="searchBouquets()">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-search text-rose-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="lg:w-80">
                    <select id="categoryFilter" onchange="filterByCategory()"
                        class="w-full px-4 py-3 border border-rose-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Kategori</option>
                        @foreach($bouquetCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Category Chips -->
            @if($bouquetCategories->count() > 0)
                <div class="flex flex-wrap gap-3 justify-center mt-6">
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200 active"
                        data-category="" onclick="selectBouquetCategory(this)">
                        <span class="mr-2">💐</span>Semua
                    </button>
                    @foreach($bouquetCategories as $category)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                            data-category="{{ $category->id }}" onclick="selectBouquetCategory(this)">
                            <span class="mr-2">💐</span>{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Bouquet Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="bouquetGrid">
            @forelse($bouquets as $bouquet)
                <div class="bouquet-card group" data-name="{{ strtolower($bouquet->name) }}"
                    data-bouquet-category="{{ $bouquet->category_id ?? '' }}">
                    <div class="card-hover glass-effect rounded-2xl shadow-lg p-4 h-full flex flex-col overflow-hidden">
                        <!-- Image -->
                        <div class="relative h-40 mb-4 rounded-xl overflow-hidden">
                            @if($bouquet->image)
                                <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl">
                                    <i class="bi bi-flower3 text-3xl text-rose-400"></i>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <!-- Wishlist Button -->
                            <button
                                class="absolute top-3 right-3 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <i class="bi bi-heart text-rose-500 text-sm"></i>
                            </button>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 flex flex-col">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-bold text-sm text-gray-800 line-clamp-2 flex-1">{{ $bouquet->name }}</h3>
                                <span
                                    class="ml-2 px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium whitespace-nowrap">
                                    {{ $bouquet->category->name ?? 'Bouquet' }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $bouquet->description }}</p>

                            <!-- Sizes -->
                            <div class="mb-3">
                                <span class="text-xs text-gray-500 block mb-1">Ukuran Tersedia:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($bouquet->sizes as $size)
                                        <span
                                            class="inline-block px-2 py-0.5 bg-gradient-to-r from-rose-100 to-pink-100 text-rose-700 rounded-full text-[10px] font-medium">
                                            {{ $size->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                @php
                                    $minPrice = $bouquet->prices->min('price');
                                    $maxPrice = $bouquet->prices->max('price');
                                @endphp
                                @if($minPrice && $maxPrice)
                                    <div class="text-center">
                                        @if($minPrice == $maxPrice)
                                            <span class="text-lg font-bold text-rose-600">
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-rose-600">
                                                Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto space-y-2">
                                @if($bouquet->prices->count() == 1)
                                    <button onclick="addToCartWithPrice('{{ $bouquet->id }}', 'bouquet')"
                                        class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                                        <i class="bi bi-cart-plus mr-2"></i>Tambah ke Keranjang
                                    </button>
                                @else
                                    <button
                                        onclick="showBouquetPriceModal('{{ $bouquet->id }}', '{{ $bouquet->name }}', {{ json_encode($bouquet->prices) }})"
                                        class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm"
                                        data-bouquet-id="{{ $bouquet->id }}" data-bouquet-name="{{ $bouquet->name }}"
                                        data-bouquet-prices="{{ htmlspecialchars(json_encode($bouquet->prices), ENT_QUOTES, 'UTF-8') }}">
                                        <i class="bi bi-cart-plus mr-2"></i>Pilih Ukuran
                                    </button>
                                @endif

                                <button onclick="showBouquetDetailPanel({{ $bouquet->id }})"
                                    class="block w-full text-center border-2 border-rose-200 text-rose-600 hover:bg-rose-50 font-semibold py-2 px-4 rounded-xl transition-all duration-200 text-sm">
                                    <i class="bi bi-eye mr-2"></i>Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="bi bi-flower3 text-2xl text-rose-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bouquet yang tersedia saat ini</h3>
                    <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gradient-to-r from-rose-600 to-pink-600 text-white py-12 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                <img src="{{ asset('logo-fellie-02.png') }}" alt="Logo" class="brand-logo rounded-full w-12 h-12">
            </div>
            <h3 class="text-2xl font-bold mb-2">Fellie Florist</h3>
            <p class="text-rose-100 mb-4 max-w-2xl mx-auto">
                Menghadirkan keindahan bunga segar berkualitas premium untuk setiap momen berharga dalam hidup Anda
            </p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="https://www.instagram.com/fellieflorist/"
                    class="text-rose-200 hover:text-white transition-colors">
                    <i class="bi bi-instagram text-xl"></i>
                </a>
                <a href="https://www.tiktok.com/@fellieflorist" class="text-rose-200 hover:text-white transition-colors"
                    target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-tiktok text-xl"></i>
                </a>
                <a href="https://wa.me/6282177929879?text=Halo%20Fellie%20!"
                    class="text-rose-200 hover:text-white transition-colors" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp text-xl"></i>
                </a>
            </div>
            <p class="text-rose-200 text-sm">© 2025 Fellie Florist. All rights reserved.</p>
        </div>
    </footer>

    <!-- Include Cart Components -->
    @include('public.partials.cart-modal')
    @include('components.bouquet-price-modal')
    @include('components.bouquet-detail-panel')

    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        // Search functionality
        function searchBouquets() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const bouquetCards = document.querySelectorAll('.bouquet-card');

            bouquetCards.forEach(card => {
                const bouquetName = card.dataset.name;
                const isVisible = bouquetName.includes(searchTerm);
                card.style.display = isVisible ? 'block' : 'none';
            });
        }

        // Category filter
        function filterByCategory() {
            const selectedCategory = document.getElementById('categoryFilter').value;
            const bouquetCards = document.querySelectorAll('.bouquet-card');

            bouquetCards.forEach(card => {
                const cardCategory = card.dataset.bouquetCategory;
                const isVisible = !selectedCategory || cardCategory === selectedCategory;
                card.style.display = isVisible ? 'block' : 'none';
            });
        }

        // Category chips
        function selectBouquetCategory(button) {
            // Remove active class from all buttons
            document.querySelectorAll('.chip-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-gradient-to-r', 'from-rose-500', 'to-pink-500', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700');
            });

            // Add active class to clicked button
            button.classList.add('active', 'bg-gradient-to-r', 'from-rose-500', 'to-pink-500', 'text-white');
            button.classList.remove('bg-white', 'text-gray-700');

            // Filter bouquets
            const selectedCategory = button.dataset.category;
            const bouquetCards = document.querySelectorAll('.bouquet-card');

            bouquetCards.forEach(card => {
                const cardCategory = card.dataset.bouquetCategory;
                const isVisible = !selectedCategory || cardCategory === selectedCategory;
                card.style.display = isVisible ? 'block' : 'none';
            });

            // Update select dropdown
            document.getElementById('categoryFilter').value = selectedCategory;
        }

        // Show bouquet price modal  
        function showBouquetPriceModal(bouquetId, bouquetName, prices) {
            // Debug logging
            console.log('showBouquetPriceModal called with:');
            console.log('- bouquetId:', bouquetId);
            console.log('- bouquetName:', bouquetName);
            console.log('- prices (raw):', prices);

            // Parse prices if it's a string
            if (typeof prices === 'string') {
                try {
                    prices = JSON.parse(prices);
                    console.log('- prices (parsed):', prices);
                } catch (e) {
                    console.error('Error parsing prices:', e);
                    alert('Error: Data harga tidak valid');
                    return;
                }
            }

            // Pastikan modal element tersedia
            const modal = document.getElementById('bouquetPriceModal');
            if (!modal) {
                console.error('Bouquet price modal not found');
                alert('Modal tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            // Check if prices array is valid
            if (!Array.isArray(prices) || prices.length === 0) {
                console.error('Invalid prices data:', prices);
                alert('Data harga tidak tersedia untuk bouquet ini.');
                return;
            }

            // Call the modal function
            console.log('Calling showBouquetPriceModalComponent...');
            showBouquetPriceModalComponent(bouquetId, bouquetName, prices);
        }
    </script>

    <!-- Include Greeting Card Modal -->
    @include('components.greeting-card-modal')

</body>

</html>