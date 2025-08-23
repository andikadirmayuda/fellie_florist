<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Title --}}
    <title>Product | Fellie Florist</title>
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
        // Helper function untuk format harga yang aman
        function safeFormatPrice(price) {
            // Ensure price is a number, remove any existing separators
            const numPrice = parseFloat(String(price).replace(/[,.]/g, '')) || 0;
            return numPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

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
                    alert('Terjadi masalah: ' + error.message);
                });
        }
        // Handler untuk tombol tambah ke keranjang dengan modal harga (hanya satu kali di bawah)
        function handleAddToCart(flowerId) {
            const prices = window.flowerPrices[flowerId] || [];
            if (prices.length === 1) {
                // Jika hanya 1 harga, langsung tambahkan
                addToCartWithPrice(flowerId, prices[0].type);
            } else if (prices.length > 1) {
                // Jika ada beberapa harga, tampilkan modal
                openCartPriceModal(flowerId, prices);
            } else {
                alert('Harga produk tidak tersedia.');
            }
        }
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Figtree', 'sans-serif'],
                    }
                }
            }
        }

        // Fungsi untuk toggle menu mobile
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu.classList.contains('hidden')) {
                // Tampilkan menu
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('animate-fade-in-down');
            } else {
                // Sembunyikan menu
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('animate-fade-in-down');
            }
        }

        // Tutup menu mobile ketika user klik di luar menu
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerButton = event.target.closest('[onclick="toggleMobileMenu()"]');
            
            if (!hamburgerButton && !mobileMenu.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Sinkronkan badge cart mobile dengan desktop
        const updateCartBadges = () => {
            const desktopBadge = document.getElementById('cartBadge');
            const mobileBadge = document.getElementById('cartBadgeMobile');
            if (desktopBadge && mobileBadge) {
                mobileBadge.textContent = desktopBadge.textContent;
                mobileBadge.classList.toggle('hidden', desktopBadge.classList.contains('hidden'));
            }
        };

        // Observer untuk memantau perubahan pada badge desktop
        const observer = new MutationObserver(updateCartBadges);
        const desktopBadge = document.getElementById('cartBadge');
        if (desktopBadge) {
            observer.observe(desktopBadge, { 
                attributes: true, 
                childList: true, 
                characterData: true 
            });
        }
    </script>
    <style>
        body,
        .font-sans {
            font-family: 'Figtree', sans-serif;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.2s ease-out;
        }

        /* Navigation Styles */
        .nav-tab {
            position: relative;
            overflow: hidden;
            text-align: center;
            white-space: nowrap;
            letter-spacing: 0.3px;
            backdrop-filter: blur(8px);
            font-size: 0.8125rem;
            padding: 0.5rem 0.75rem;
        }

        @media (max-width: 360px) {
            .nav-tab {
                min-width: 80px !important;
                padding: 0.5rem !important;
                font-size: 0.75rem !important;
            }
        }

        @media (min-width: 361px) and (max-width: 639px) {
            .nav-tab {
                min-width: 90px !important;
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8125rem !important;
            }
        }

        @media (min-width: 640px) and (max-width: 767px) {
            .nav-tab {
                min-width: 100px !important;
                padding: 0.625rem 1rem !important;
            }
        }

        @media (min-width: 768px) {
            .nav-tab {
                min-width: 120px;
                padding: 0.75rem 1.25rem;
                font-size: 0.875rem;
            }
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .nav-tab:hover::before {
            transform: translateX(100%);
        }

        /* Active tab indicator animation */
        @keyframes dotPulse {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0.5;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0.5;
            }
        }

        .nav-tab span.absolute {
            animation: dotPulse 2s infinite;
        }

        /* Responsive navigation adjustments */
        @media (max-width: 640px) {
            .nav-tab {
                min-width: 100px;
            }

            .nav-tab span {
                font-size: 0.8125rem;
            }
        }

        @media (min-width: 641px) {
            .nav-tab {
                min-width: 120px;
            }

            .nav-tab:hover {
                transform: translateY(-1px);
            }
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

        /* Label Promo - Ribbon Style */
        .flower-card .relative,
        .bouquet-card .relative {
            position: relative;
        }

        .promo-label {
            position: absolute;
            top: 10px;
            left: -35px;
            /* geser supaya miringnya pas */
            background-color: rgb(255, 0, 132);
            color: rgb(255, 255, 255);
            font-weight: bold;
            padding: 5px 40px;
            transform: rotate(-45deg);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            font-size: 14px;
            text-transform: uppercase;
            z-index: 10;
        }

        /* Price Tag Animations */
        @keyframes swing {

            0%,
            100% {
                transform: rotate(0deg) translateX(-50%);
            }

            25% {
                transform: rotate(2deg) translateX(-50%);
            }

            75% {
                transform: rotate(-2deg) translateX(-50%);
            }
        }

        .animate-swing {
            animation: swing 3s ease-in-out infinite;
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: rotate(12deg) translateY(0px);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: rotate(12deg) translateY(-4px);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }

        @keyframes slide-shine {
            0% {
                transform: translateX(-100%) skewX(-12deg);
            }

            100% {
                transform: translateX(200%) skewX(-12deg);
            }
        }

        .animate-slide-shine {
            animation: slide-shine 3s ease-in-out infinite;
        }

        /* Compact Price Tag */
        /* Removed old compact tag styles */

        /* Subtle animations - removed unused animations */

        /* Flexible card heights - auto-adjust based on content */
        .flower-card,
        .bouquet-card {
            min-height: auto;
            /* Remove fixed height for better flexibility */
        }

        /* Consistent content spacing */
        .product-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Better text handling for long names */
        .product-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Limit to 2 lines for better layout */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.25;
            height: 2.5em;
            /* Fixed height for consistency */
            word-wrap: break-word;
            overflow-wrap: break-word;
            margin-bottom: 0.125rem;
            /* Very close to description */
        }

        /* Compact spacing for product cards */
        .flower-card .product-title+p,
        .bouquet-card .product-title+p {
            margin-top: 0.125rem;
            /* Very close to title */
            margin-bottom: 0.25rem;
        }

        .flower-card .text-price,
        .bouquet-card .text-price {
            margin-top: 0.25rem;
            /* Reduced top margin for price */
        }

        /* Responsive category badge */
        .category-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            white-space: nowrap;
            display: inline-block;
            cursor: help;
            transition: all 0.2s ease;
        }

        .category-badge:hover {
            background-color: rgb(244 63 94);
            color: white;
            transform: scale(1.05);
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {

            .flower-card,
            .bouquet-card {
                min-height: auto;
            }

            .product-title {
                font-size: 0.875rem;
                line-height: 1.2;
                height: 2.4em;
                /* Fixed height for mobile */
                -webkit-line-clamp: 2;
            }

            .category-badge {
                font-size: 0.55rem;
                padding: 0.15rem 0.4rem;
                max-width: none;
                /* Remove max-width to allow full text display */
                white-space: nowrap;
                /* Ensure text stays on one line */
            }

            .flower-card .text-price,
            .bouquet-card .text-price {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }

            /* Compact layout for mobile 2-column */
            .card-hover {
                padding: 0.5rem;
            }

            .relative.h-36 {
                height: 120px;
            }

            .mb-3 {
                margin-bottom: 0.25rem;
                /* Reduced spacing on mobile */
            }

            .text-xs {
                font-size: 0.65rem;
            }
        }

        /* Desktop optimizations */
        @media (min-width: 641px) {
            .product-title {
                font-size: 0.95rem;
                line-height: 1.25;
                height: 2.5em;
                /* Fixed height for desktop */
                -webkit-line-clamp: 2;
            }

            .category-badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.5rem;
            }
        }

        /* Large screen optimizations */
        @media (min-width: 1024px) {
            .product-title {
                font-size: 1rem;
                line-height: 1.3;
                height: 2.6em;
                /* Slightly more space for larger screens */
            }

            .category-badge {
                font-size: 0.7rem;
            }
        }

        /* Grid responsiveness improvements */
        .product-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .product-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Card content improvements */
        .card-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0.75rem;
        }

        @media (min-width: 640px) {
            .card-content {
                padding: 1rem;
            }
        }

        /* Title and category wrapper */
        .title-section {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Auto-sizing for title and category */
        .title-text {
            flex: 1;
            min-width: 0;
            /* Allow text to shrink */
        }

        .category-text {
            flex-shrink: 0;
            /* Don't shrink category badge */
        }

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

        /* Enhanced Navigation Styles */
        .nav-tab {
            position: relative;
            overflow: hidden;
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .nav-tab:hover::before {
            left: 100%;
        }

        /* Enhanced responsive design */
        @media (max-width: 640px) {
            .nav-mobile-text {
                font-size: 0.75rem;
                line-height: 1rem;
            }

            .nav-icon {
                width: 1.5rem;
                height: 1.5rem;
            }
        }

        @media (min-width: 641px) and (max-width: 768px) {
            .nav-tablet-spacing {
                margin-left: 1rem;
                margin-right: 1rem;
            }
        }

        /* Smooth hover animations */
        .nav-hover-effect {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-hover-effect:hover {
            transform: translateY(-2px);
        }

        /* Active tab gradient animation */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .nav-active-gradient {
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }
    </style>
</head>

<body class="min-h-screen gradient-bg text-black flex flex-col font-sans overflow-x-hidden">
    @include('public.partials.cart-modal')
    @include('public.partials.cart-panel')

    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-40 transition-transform duration-300" id="main-header">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col items-center">
                <!-- Top Bar -->
                <div class="w-full flex items-center justify-between h-20 md:h-24 pl-7 mt-2">
                    <!-- Brand Text - Left -->
                    <div class="flex items-center">
                        <a href="{{ route('public.flowers') }}" class="flex items-center">
                            <div>
                                <h1 class="text-sm md:text-xl lg:text-2xl font-bold text-gray-800">Fellie Florist</h1>
                                <p class="text-[7px] sm:text-xs text-gray-500">Suplier Bunga Palembang</p>
                            </div>
                        </a>
                    </div>
    
                    <!-- Logo - Center -->
                    <div class="absolute left-1/2 transform -translate-x-1/2">
                        <img src="{{ asset('logo-fellie-02.png') }}" alt="Logo"
                            {{-- class="brand-logo w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 rounded-full shadow-lg hover:shadow-xl transition-all duration-300"> --}}
                            class="brand-logo w-20 h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 scale-90 rounded-full shadow-lg hover:shadow-xl
                            transition-all duration-300">

                    </div>
    
                    <!-- Action Buttons -->
                    <div class="flex items-center pr-7">
                        <!-- Desktop Menu -->
                        <div class="hidden md:flex items-center space-x-3">
                            <!-- Track Order -->
                            <a href="{{ route('public.order.track') }}"
                                class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                                title="Lacak Pesanan">
                                <i class="bi bi-truck text-xl"></i>
                            </a>
        
                            @if(session('last_public_order_code'))
                                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                                    class="relative text-white bg-rose-500 hover:bg-rose-600 p-1.5 rounded-full hover:shadow-lg transition-all duration-200">
                                    <i class="bi bi-receipt-cutoff text-xl"></i>
                                    <span
                                        class="absolute -top-1 -right-1 bg-green-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">✓</span>
                                </a>
                            @endif
        
                            <!-- Cart -->
                            <button onclick="toggleCart()"
                                class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                                title="Keranjang Belanja">
                                <i class="bi bi-bag text-xl"></i>
                                <span id="cartBadge"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[10px] rounded-full flex items-center justify-center hidden">0</span>
                            </button>
        
                            <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200">
                                <i class="bi bi-person-circle text-xl"></i>
                            </a>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="md:hidden flex items-center space-x-2">
                            <!-- Cart Button - Always Visible -->
                            <button onclick="toggleCart()"
                                class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                                title="Keranjang Belanja">
                                <i class="bi bi-bag text-xl"></i>
                                <span id="cartBadgeMobile"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[10px] rounded-full flex items-center justify-center hidden">0</span>
                            </button>

                            <!-- Hamburger Button -->
                            <button onclick="toggleMobileMenu()"
                                class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200">
                                <i class="bi bi-list text-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Menu Dropdown -->
                    <div id="mobileMenu" class="hidden fixed top-[80px] right-0 w-48 bg-white shadow-lg rounded-bl-lg z-50">
                        <div class="py-2">
                            <a href="{{ route('public.order.track') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-rose-50 hover:text-rose-600">
                                <i class="bi bi-truck mr-2"></i>
                                <span>Lacak Pesanan</span>
                            </a>
                            
                            @if(session('last_public_order_code'))
                                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                                    class="flex items-center px-4 py-2 text-gray-600 hover:bg-rose-50 hover:text-rose-600">
                                    <i class="bi bi-receipt-cutoff mr-2"></i>
                                    <span>Pesanan Terakhir</span>
                                </a>
                            @endif

                            <a href="{{ route('login') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-rose-50 hover:text-rose-600">
                                <i class="bi bi-person-circle mr-2"></i>
                                <span>Login</span>
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Main Navigation -->
                <div class="w-full mt-4">
                    <nav class="flex items-center justify-center">
                        <div class="flex items-center justify-center space-x-2 sm:space-x-4 md:space-x-8">
                            <a href="{{ route('public.flowers') }}"
                                class="px-2 sm:px-4 py-1.5 text-center {{ $activeTab === 'flowers' ? 'nav-tab nav-hover-effect group relative items-center space-x-2 px-3 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-rose-500 to-red-500 text-white shadow-lg nav-active-gradient' : '' }}">
                                <span class="text-sm font-medium">BUNGA</span>
                            </a>

                            <a href="{{ route('public.bouquets') }}"
                                class="px-2 sm:px-4 py-1.5 text-center {{ $activeTab === 'bouquets' ? 'bg-gray-100 rounded-lg' : '' }}">
                                <span class="text-sm font-medium">BOUQUET</span>
                            </a>

                            <a href="{{ route('custom.bouquet.create') }}" class="px-2 sm:px-4 py-1.5 text-center">
                                <span class="text-sm font-medium">CUSTOM</span>
                            </a>
                        </div>
                    </nav>
                    </div>
                    <!-- Add spacing below main navigation -->
                    <div class="mb-6"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="w-full max-w-6xl mx-auto px-4 py-6">
        <!-- Tab Navigation (Duplicate removed, using header navigation) -->

        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col items-center">
            <!-- Enhanced Search Bar -->
            <div class="w-full max-w-2xl mb-4">
                <div class="relative">
                    <i
                        class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" id="searchInput"
                        placeholder="{{ $activeTab === 'flowers' ? 'Cari bunga impian Anda...' : 'Cari bouquet impian Anda...' }}"
                        class="w-full pl-12 pr-4 py-4 text-lg border-2 border-rose-200 rounded-2xl focus:ring-2 focus:ring-rose-400 focus:border-rose-400 focus:outline-none shadow-lg transition-all duration-200 bg-white/90"
                        oninput="filterItems()">
                </div>
            </div>

            <!-- Enhanced Filter Chips -->
            @if($activeTab === 'flowers')
                <div class="flex flex-wrap gap-3 justify-center">
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200 active"
                        data-category="" onclick="selectCategory(this)">
                        <span class="mr-2">🌸</span>Semua
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                        data-category="Fresh Flowers" onclick="selectCategory(this)">
                        <span class="mr-2">🌿</span>Fresh Flowers
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                        data-category="Artificial" onclick="selectCategory(this)">
                        <span class="mr-2">🍁</span>Artificial
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                        data-category="Daun" onclick="selectCategory(this)">
                        <span class="mr-2">🍃</span>Daun
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                        data-category="Aksesoris" onclick="selectCategory(this)">
                        <span class="mr-2">🎀</span>Aksesoris
                    </button>
                </div>
            @else
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach($bouquetCategories as $category)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                            data-category="{{ (int) $category->id }}" onclick="selectBouquetCategory(this)">
                            <span class="mr-2">�</span>{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
            @if($activeTab === 'flowers')
                @forelse($flowers as $flower)
                    <div class="flower-card group" data-category="{{ $flower->category->name ?? 'lainnya' }}"
                        data-name="{{ strtolower($flower->name) }}" data-flower-id="{{ (int) $flower->id }}">
                        <div
                            class="card-hover glass-effect rounded-2xl shadow-lg p-3 sm:p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-36 sm:h-40 mb-3 sm:mb-4 rounded-xl overflow-hidden">
                                @if($flower->image)
                                    <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl">
                                        {{-- <i class="bi bi-flower1 text-3xl text-rose-400">🌸</i> --}}
                                        <span class="text-4xl">🌸</span>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <!-- Label Promo - Ribbon Style -->
                                @if($flower->prices->where('type', 'promo')->isNotEmpty())
                                    <div class="promo-label">
                                        PROMO
                                    </div>
                                @endif

                                <!-- Wishlist Button -->
                                <button
                                    class="absolute top-2 sm:top-3 right-2 sm:right-3 w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <i class="bi bi-heart text-rose-500 text-xs sm:text-sm"></i>
                                </button>
                            </div>

                            <!-- Details -->
                            <div class="product-content flex-1">
                                <!-- Category Badge - Centered -->
                                <div class="flex justify-center mb-2">
                                    <span
                                        class="category-badge bg-rose-100 text-rose-700 rounded-full px-3 py-1 text-xs font-medium"
                                        title="{{ $flower->category->name ?? 'Umum' }}">
                                        {{ $flower->category->name ?? 'Umum' }}
                                    </span>
                                </div>

                                <!-- Product Title - Centered -->
                                <h3 class="product-title font-bold text-gray-800 text-center leading-none mt-5">
                                    {{ $flower->name }}
                                </h3>

                                <!-- Price - Centered -->
                                <div>
                                    @php
        // Siapkan array harga untuk JS
        $jsPrices = $flower->prices->map(function ($price) {
            return [
                'id' => $price->id,
                'type' => $price->type,
                'label' => __(ucwords(str_replace('_', ' ', $price->type))),
                'price' => (int) $price->price // Pastikan price adalah integer
            ];
        });
                                    @endphp
                                    <div class="text-center">
                                        <div class="text-price text-sm sm:text-lg font-bold text-rose-600 text-center">
                                            @php
        $minPrice = $jsPrices->min('price');
        $maxPrice = $jsPrices->max('price');
                                            @endphp
                                            @if($minPrice === $maxPrice)
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 text-center">
                                            {{ $jsPrices->count() }} pilihan harga
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock -->
                                <div class="mb-2">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-gray-500">Stok:</span>
                                        <span
                                            class="font-semibold {{ $flower->current_stock > 10 ? 'text-green-600' : ($flower->current_stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            @php
        // Cari harga ikat yang tersedia, prioritas ikat 5
        $ikatPrice = $flower->prices->firstWhere('type', 'ikat_5')
            ?: $flower->prices->firstWhere('type', 'ikat 5')
            ?: $flower->prices->firstWhere('type', 'ikat_10')
            ?: $flower->prices->firstWhere('type', 'ikat 10')
            ?: $flower->prices->firstWhere('type', 'ikat_20')
            ?: $flower->prices->firstWhere('type', 'ikat 20');

        $ikatCount = 0;
        $ikatLabel = '';

        if ($ikatPrice && $ikatPrice->unit_equivalent > 0) {
            $ikatCount = floor($flower->current_stock / $ikatPrice->unit_equivalent);
            $unitSize = $ikatPrice->unit_equivalent;
            $ikatLabel = " / {$ikatCount} ikat";
        }

        // Gunakan base_unit dari database atau default ke 'tangkai'
        $baseUnit = $flower->base_unit ?? 'tangkai';
                                            @endphp
                                            {{ $flower->current_stock }} {{ $baseUnit }}{{ $ikatLabel }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-rose-400 to-pink-500 h-1.5 rounded-full transition-all duration-300"
                                            style="width: {{ min(($flower->current_stock / 50) * 100, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                @php $isOut = (int) $flower->current_stock <= 0; @endphp
                                <button onclick="{{ $isOut ? 'return false' : 'handleAddToCart(' . (int) $flower->id . ')' }}"
                                    class="mt-auto w-full {{ $isOut ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white shadow-md hover:shadow-lg' }} font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 text-xs sm:text-sm"
                                    {{ $isOut ? 'disabled' : '' }}>
                                    @if($isOut)
                                        <i class="bi bi-x-circle mr-1 sm:mr-2"></i>Stok Habis
                                    @else
                                        <i class="bi bi-cart-plus mr-1 sm:mr-2"></i>Tambah ke Keranjang
                                    @endif
                                </button>
                                <script>
                                    window.flowerPrices = window.flowerPrices || {};
                                    try {
                                        const pricesData = @json($jsPrices);
                                        // Validasi dan sanitasi data harga, filter tipe rangkaian
                                        const sanitizedPrices = pricesData
                                            .filter(price => !['custom_ikat', 'custom_tangkai', 'custom_khusus'].includes(price.type))
                                            .map(price => ({
                                                id: parseInt(price.id) || 0,
                                                type: price.type || '',
                                                label: price.label || '',
                                                price: parseFloat(String(price.price).replace(/[,.]/g, '')) || 0
                                            }));
                                        window.flowerPrices[{{ (int) $flower->id }}] = sanitizedPrices;
                                    } catch (error) {
                                        console.error('Error parsing flower prices:', error);
                                        window.flowerPrices[{{ (int) $flower->id }}] = [];
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="bi bi-flower1 text-2xl text-rose-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bunga yang tersedia saat ini</h3>
                        <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.
                        </p>
                    </div>
                @endforelse
            @else
                @forelse($bouquets as $bouquet)
                    <div class="bouquet-card group" data-name="{{ strtolower($bouquet->name) }}"
                        data-bouquet-category="{{ $bouquet->category_id ? (int) $bouquet->category_id : '' }}">
                        <div
                            class="card-hover glass-effect rounded-2xl shadow-lg p-3 sm:p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-36 sm:h-40 mb-3 sm:mb-4 rounded-xl overflow-hidden">
                                @if($bouquet->image)
                                    <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    {{-- gradient placeholder --}}
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
                                    class="absolute top-2 sm:top-3 right-2 sm:right-3 w-6 sm:w-8 h-6 sm:h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <i class="bi bi-heart text-rose-500 text-xs sm:text-sm"></i>
                                </button>
                            </div>

                            <!-- Details -->
                            <div class="product-content flex-1">
                                <!-- Category Badge - Left Aligned -->
                                <div class="flex justify-start mb-3">
                                    <span
                                        class="category-badge bg-rose-100 text-rose-700 rounded-full px-3 py-1 text-xs font-medium"
                                        title="{{ $bouquet->category->name ?? 'Bouquet' }}">
                                        {{ $bouquet->category->name ?? 'Bouquet' }}
                                    </span>
                                </div>

                                <!-- Product Title - Left Aligned -->
                                <h3 class="product-title font-bold text-gray-800 mb-0 text-left leading-tight">
                                    {{ $bouquet->name }}
                                </h3>

                                <!-- Description - Left Aligned with no spacing from title -->
                                <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2 leading-tight text-left -mt-1">
                                    {{ $bouquet->description }}
                                </p>

                                <!-- Sizes - Left Aligned -->
                                <div class="mb-1">
                                    <span class="text-xs text-gray-500 block mb-1 text-left">Ukuran Tersedia:</span>
                                    <div class="flex flex-wrap gap-1 justify-start">
                                        @foreach($bouquet->sizes as $size)
                                            <span
                                                class="inline-block px-2 py-1 bg-gradient-to-r from-rose-100 to-pink-100 text-rose-700 rounded-full text-[10px] sm:text-xs font-medium">
                                                {{ $size->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Price Range - Left Aligned -->
                                <div class="mb-1">
                                    @php
        $minPrice = $bouquet->prices->min('price');
        $maxPrice = $bouquet->prices->max('price');
                                    @endphp
                                    <div class="text-left">
                                        <div class="text-price text-sm sm:text-lg font-bold text-rose-600">
                                            @if($minPrice === $maxPrice)
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">rentang harga</div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('public.bouquet.detail', $bouquet->id) }}"
                                    class="mt-auto w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-xs sm:text-sm text-center block">
                                    <i class="bi bi-eye mr-1 sm:mr-2"></i>Lihat Detail
                                </a>
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
            @endif
        </div>

        <!-- Call to Action untuk Bouquet (hanya tampil di tab flowers) -->
        @if($activeTab === 'flowers')
            <div class="mt-16 text-center">
                <div
                    class="bg-gradient-to-br from-rose-100 via-pink-50 to-orange-50 rounded-3xl p-8 shadow-lg border border-rose-200">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-600 rounded-full mb-6 shadow-lg">
                        <i class="bi bi-flower3 text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Lihat Koleksi Bouquet Kami</h3>
                    <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                        Rangkaian bunga cantik yang dirancang khusus untuk momen spesial Anda.
                        Berbagai ukuran dan kategori tersedia untuk setiap kebutuhan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('public.bouquets') }}"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="bi bi-flower3 mr-2"></i>
                            Lihat Semua Bouquet
                        </a>
                        <span class="text-sm text-gray-500">
                            atau klik tab "💐 Bouquet" di atas
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gradient-to-r from-rose-600 to-pink-600 text-white py-12 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                {{-- <span class="text-white font-bold text-2xl">F</span> --}}
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

    <script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>
    <script>
        let selectedCategory = '';
        let selectedBouquetCategory = '';
        function filterItems() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const activeTab = '{{ $activeTab }}';
            if (activeTab === 'flowers') {
                document.querySelectorAll('.flower-card').forEach(card => {
                    const name = card.getAttribute('data-name');
                    const category = card.getAttribute('data-category');
                    const matchSearch = name.includes(search);
                    const matchCategory = !selectedCategory || category === selectedCategory;
                    card.style.display = (matchSearch && matchCategory) ? '' : 'none';
                });
            } else {
                document.querySelectorAll('.bouquet-card').forEach(card => {
                    const name = card.getAttribute('data-name');
                    const category = card.getAttribute('data-bouquet-category');
                    const matchSearch = name.includes(search);
                    const matchCategory = !selectedBouquetCategory || category === selectedBouquetCategory;
                    card.style.display = (matchSearch && matchCategory) ? '' : 'none';
                });
            }
        }
        function selectCategory(btn) {
            selectedCategory = btn.getAttribute('data-category');
            document.querySelectorAll('.chip-btn').forEach(button => {
                button.classList.remove('bg-rose-500', 'text-white', 'border-rose-500');
                button.classList.add('bg-white', 'text-gray-700', 'border-rose-200');
            });
            btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500');
            btn.classList.remove('bg-white', 'text-gray-700', 'border-rose-200');
            filterItems();
        }
        function selectBouquetCategory(btn) {
            selectedBouquetCategory = btn.getAttribute('data-category');
            console.log('Selected bouquet category:', selectedBouquetCategory);
            document.querySelectorAll('.chip-btn').forEach(button => {
                button.classList.remove('bg-rose-500', 'text-white', 'border-rose-500');
                button.classList.add('bg-white', 'text-gray-700', 'border-rose-200');
            });
            btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500');
            btn.classList.remove('bg-white', 'text-gray-700', 'border-rose-200');
            filterItems();
        }
        document.addEventListener('DOMContentLoaded', function () {
            const activeTab = '{{ $activeTab }}';
            const firstCategoryBtn = document.querySelector('.chip-btn');
            if (firstCategoryBtn) {
                if (activeTab === 'flowers') {
                    selectCategory(firstCategoryBtn);
                } else {
                    selectBouquetCategory(firstCategoryBtn);
                }
            }
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function () {
                    this.style.opacity = '1';
                });
            });
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterItems, 300);
            });
        });
    </script>

    <!-- Include Greeting Card Modal -->
    @include('components.greeting-card-modal')

    <script>
        // Header hide/show on scroll
        let lastScrollY = window.scrollY;
        const header = document.getElementById('main-header');
        let ticking = false;
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const currentScrollY = window.scrollY;
                    
                    // Jika scroll ke bawah dan sudah scroll lebih dari 100px
                    if (currentScrollY > lastScrollY && currentScrollY > 100) {
                        header.style.transform = 'translateY(-100%)';
                    } else {
                        header.style.transform = 'translateY(0)';
                    }
                    
                    lastScrollY = currentScrollY;
                    ticking = false;
                });
                
                ticking = true;
            }
        });
    </script>
</body>

</html>