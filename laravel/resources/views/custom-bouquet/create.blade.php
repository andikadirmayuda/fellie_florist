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
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
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

        /* Consistent card heights */
        .product-card {
            min-height: 300px;
        }

        @media (min-width: 640px) {
            .product-card {
                min-height: 350px;
            }
        }

        /* Better text sizing for mobile */
        @media (max-width: 639px) {
            .product-card .text-price {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
        }

        /* Animation untuk order detail icon */
        .order-detail-pulse {
            animation: orderPulse 2s infinite;
        }

        @keyframes orderPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        .notification-badge {
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-3px);
            }

            60% {
                transform: translateY(-1px);
            }
        }

        /* Custom Bouquet Specific Styles */
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced chip button styles */
        .chip-btn.active {
            background: linear-gradient(135deg, #f43f5e, #ec4899);
            color: white;
            border-color: #f43f5e;
            transform: translateY(-2px);
        }

        .chip-btn:hover {
            border-color: #f43f5e;
            background-color: #fef2f2;
        }

        .category-tab.active {
            border-color: #f43f5e;
            color: white;
            background: linear-gradient(135deg, #f43f5e, #ec4899);
        }

        #selectedItems:empty:before {
            content: '';
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Horizontal Builder Styles */
        .horizontal-builder {
            transition: all 0.3s ease-in-out;
        }

        .horizontal-builder .stats-card {
            transition: transform 0.2s ease-in-out;
        }

        .horizontal-builder .stats-card:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .horizontal-builder .flex-col {
                gap: 1rem;
            }

            .horizontal-builder .stats-card {
                min-width: 100px;
            }
        }

        /* Better mobile experience for selected items */
        @media (max-width: 640px) {
            .selected-item-card {
                padding: 0.75rem;
            }

            .selected-item-card .quantity-controls {
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg text-black flex flex-col font-sans overflow-x-hidden">
    @include('public.partials.cart-modal')
    @include('public.partials.cart-panel')

    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Top Bar -->
            <div class="flex items-center justify-between h-16">
                <!-- Brand Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('logo-fellie-02.png') }}" alt="Logo"
                            class="brand-logo w-10 h-10 rounded-full">
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Fellie Florist</h1>
                            <p class="text-xs text-gray-500">Custom Bouquet Builder</p>
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

    <!-- Hero Section -->
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 py-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">Custom
                </span>Bouquet
            </h2>
            <p class="text-gray-600 mb-2">Buat bouquet impian Anda dengan memilih bunga sesuai keinginan</p>
            <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                <i class="bi bi-clock text-rose-400"></i>
                Terakhir diperbarui:
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
            </p>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="flex justify-center space-x-8 py-4">
        <a href="{{ route('public.flowers') }}"
            class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700">
            🌸 Bunga
        </a>
        <a href="{{ route('public.bouquets') }}"
            class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700">
            💐 Bouquet
        </a>
        <a href="{{ route('custom.bouquet.create') }}"
            class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 text-purple-600 hover:text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg">
            🎨 Custom Bouquet
            <span
                class="absolute -top-1 -right-1 text-xs bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-1.5 py-0.5 rounded-full">Baru!</span>
            <div
                class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-full">
            </div>
        </a>
    </nav>

    <!-- Main Content -->
    <div class="w-full max-w-6xl mx-auto px-4 py-6">
        <!-- Status Bar -->
        <div class="mb-6 flex items-center justify-center gap-4 text-sm">
            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                <i class="bi bi-palette2 mr-1"></i>
                Draft ID: #{{ $customBouquet->id }}
            </span>
            <span class="bg-rose-100 text-rose-800 px-3 py-1 rounded-full font-semibold" id="totalPrice">
                <i class="bi bi-currency-dollar mr-1"></i>
                Rp {{ number_format($customBouquet->total_price, 0, ',', '.') }}
            </span>
        </div>

        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col items-center">
            <!-- Enhanced Filter Chips -->
            <div class="flex flex-wrap gap-3 justify-center">
                <button
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200 active"
                    data-category="">
                    <span class="mr-2">🌺</span>Semua Produk
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Fresh Flowers">
                    <span class="mr-2">🌿</span>Fresh Flowers
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Artificial">
                    <span class="mr-2">🍁</span>Artificial
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Daun">
                    <span class="mr-2">🍃</span>Daun
                </button>
                <button type="button"
                    class="category-tab chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                    data-category="Aksesoris">
                    <span class="mr-2">🎀</span>Aksesoris
                </button>
            </div>
        </div>

        <!-- Horizontal Bouquet Builder -->
        <div class="mb-6 horizontal-builder">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Builder Header -->
                <div
                    class="bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 text-white p-4 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0"
                            style="background-image: radial-gradient(circle at 20% 20%, white 2px, transparent 2px); background-size: 20px 20px;">
                        </div>
                    </div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center mb-2 md:mb-0">
                                <span class="text-3xl mr-3">🛒</span>
                                <div>
                                    <h2 class="text-xl font-bold flex items-center">
                                        Custom Bouquet Impianmu
                                        <span
                                            class="ml-3 bg-white/20 rounded-full px-3 py-1 text-xs font-medium">v2.0</span>
                                    </h2>
                                    <p class="text-sm opacity-90 font-medium">Komponen yang dipilih</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <!-- Quick Stats -->
                                <div class="bg-white/10 rounded-lg px-4 py-2 text-center stats-card">
                                    <div class="text-xs opacity-80">Items</div>
                                    <div class="text-lg font-bold" id="itemCount">0</div>
                                </div>
                                <div class="bg-white/10 rounded-lg px-4 py-2 text-center stats-card">
                                    <div class="text-xs opacity-80">Total Harga</div>
                                    <div class="text-lg font-bold" id="builderHeaderPrice">
                                        Rp {{ number_format($customBouquet->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Builder Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Selected Items (Left) -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-xl mr-2">📋</span>
                                Item yang Dipilih
                            </h3>
                            <div id="selectedItems" class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar">
                                <div class="text-center py-8 text-gray-500">
                                    <div class="relative inline-block">
                                        <div class="text-5xl mb-3 animate-pulse-slow">🌹</div>
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-rose-100 via-pink-100 to-purple-100 rounded-full opacity-20 transform rotate-12">
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada komponen yang dipilih</p>
                                    <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                        💡 Klik <span class="text-purple-600 font-semibold">"+ Tambah ke Bouquet"</span>
                                        pada produk di bawah
                                    </p>
                                    <div class="mt-4 flex justify-center">
                                        <div
                                            class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-full px-4 py-2">
                                            <span class="text-xs text-purple-700 font-medium">Mulai membangun bouquet
                                                impian Anda</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions & Reference (Right) -->
                        <div class="lg:col-span-1">
                            <!-- Reference Upload -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <span class="text-lg mr-2">📸</span>
                                    Upload Referensi
                                    <span
                                        class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Opsional</span>
                                </label>
                                <div class="relative">
                                    <input type="file" id="referenceImage" accept="image/*" class="hidden">
                                    <button type="button" id="uploadReferenceBtn"
                                        class="w-full border-2 border-dashed border-purple-300 hover:border-purple-400 rounded-xl p-4 text-center transition-all duration-300 bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 group">
                                        <div class="text-purple-400 group-hover:text-purple-500">
                                            <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span class="text-sm font-medium">Upload gambar referensi</span>
                                            <p class="text-xs mt-1 text-gray-500">JPG, PNG hingga 5MB</p>
                                        </div>
                                    </button>
                                </div>
                                <div id="referencePreview" class="mt-3 hidden">
                                    <div class="relative rounded-xl overflow-hidden">
                                        <img id="referenceImagePreview" class="w-full h-32 object-cover"
                                            alt="Reference">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                        </div>
                                    </div>
                                    <button type="button" id="removeReferenceBtn"
                                        class="mt-2 w-full text-xs text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition-colors">
                                        🗑️ Hapus gambar referensi
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button type="button" id="addToMainCartBtn"
                                    class="w-full bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 hover:from-purple-600 hover:via-purple-700 hover:to-indigo-700 text-white font-bold py-4 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none group relative overflow-hidden"
                                    disabled>
                                    <div
                                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300">
                                    </div>
                                    <div class="relative flex items-center justify-center">
                                        <span class="text-xl mr-2">🛒</span>
                                        <span>Tambah ke Keranjang Utama</span>
                                    </div>
                                </button>

                                <div class="flex space-x-2">
                                    <button type="button" id="clearBuilderBtn"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 font-medium py-2 px-3 rounded-lg transition-all duration-200 text-sm">
                                        🗑️ Kosongkan
                                    </button>
                                    <button type="button" id="saveBuilderBtn"
                                        class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-800 font-medium py-2 px-3 rounded-lg transition-all duration-200 text-sm">
                                        💾 Simpan Draft
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Products Grid Header -->
            <div class="bg-gradient-to-r from-rose-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">🌸 Pilih Komponen Bouquet</h3>
                <p class="text-sm text-gray-600">Klik produk untuk menambahkan ke bouquet Anda</p>
            </div>
            <!-- Products Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                    id="productsGrid">
                    @foreach($products as $product)
                        <div class="product-card bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                            data-category="{{ $product->category->name ?? '' }}" data-product-id="{{ $product->id }}">

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
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </p>

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
                                    class="w-full mt-3 bg-purple-500 hover:bg-purple-600 text-white text-sm py-2 px-3 rounded-md transition-colors add-product-btn"
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
    </div>
    </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white p-4 rounded-t-xl">
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
                        class="flex-1 bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-md transition-colors">
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
                    const categoryName = this.dataset.category;
                    filterProducts(categoryName);
                });
            });
        }

        function filterProducts(categoryName) {
            document.querySelectorAll('.product-card').forEach(card => {
                const cardCategory = card.dataset.category;
                if (!categoryName || cardCategory === categoryName) {
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
            const itemCountEl = document.getElementById('itemCount');

            // Update item count
            itemCountEl.textContent = items.length;

            if (items.length === 0) {
                container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <div class="relative">
                    <div class="text-5xl mb-3 animate-pulse-slow">🌹</div>
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-100 via-pink-100 to-purple-100 rounded-full opacity-20 transform rotate-12"></div>
                </div>
                <p class="text-sm font-medium">Belum ada komponen yang dipilih</p>
                <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                    💡 Klik <span class="text-purple-600 font-semibold">"+ Tambah ke Bouquet"</span> pada produk di sebelah kiri
                </p>
                <div class="mt-4 flex justify-center">
                    <div class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-full px-4 py-2">
                        <span class="text-xs text-purple-700 font-medium">Mulai membangun bouquet impian Anda</span>
                    </div>
                </div>
            </div>
        `;
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            const itemsHtml = items.map(item => `
        <div class="bg-gradient-to-r from-white to-purple-50 rounded-lg p-3 border border-purple-100 hover:border-purple-200 transition-colors selected-item-card" data-item-id="${item.id}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900">${item.product_name}</h4>
                    <p class="text-xs text-purple-600 bg-purple-50 inline-block px-2 py-1 rounded-full mt-1">${item.price_type_display}</p>
                    <div class="flex items-center mt-2 space-x-2 quantity-controls">
                        <button class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded-full text-xs flex items-center justify-center transition-colors" 
                                onclick="updateItemQuantity(${item.id}, ${item.quantity - 1})">-</button>
                        <span class="text-sm font-bold px-2 bg-white rounded border">${item.quantity}</span>
                        <button class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded-full text-xs flex items-center justify-center transition-colors" 
                                onclick="updateItemQuantity(${item.id}, ${item.quantity + 1})">+</button>
                    </div>
                </div>
                <div class="text-right ml-3">
                    <div class="text-sm font-bold text-purple-600">Rp ${item.subtotal.toLocaleString('id-ID')}</div>
                    <button class="text-xs text-red-600 hover:text-red-800 mt-1 bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition-colors" onclick="removeItem(${item.id})">
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
            const formattedPrice = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById('totalPrice').textContent = formattedPrice;
            document.getElementById('builderHeaderPrice').textContent = formattedPrice;

            // Update item count display element if exists
            const itemCountEl = document.getElementById('itemCount');
            if (itemCountEl && itemCountEl.textContent) {
                // Item count is updated elsewhere
            }
        }

        async function addToMainCart() {
            // Check if custom bouquet has items
            const selectedItems = document.querySelectorAll('#selectedItems .bg-gradient-to-r');
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
            const selectedItems = document.querySelectorAll('#selectedItems .bg-gradient-to-r');
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

    <!-- Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>

</body>

</html>