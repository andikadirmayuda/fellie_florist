<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Product Fellie Florist</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
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
        body, .font-sans {
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
    </style>
</head>

<body class="min-h-screen gradient-bg text-black flex flex-col font-sans">
    <!-- Header -->
    <header class="w-full glass-effect border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Top Bar -->
            <div class="flex items-center justify-between h-16">
                <!-- Brand Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.flowers') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">F</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">Fellie Florist</h1>
                            <p class="text-xs text-gray-500">Premium Flower Shop</p>
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-md mx-8">
                    <div class="relative">
                        <input type="text" placeholder="Cari bunga impian Anda..." 
                            class="w-full h-10 pl-4 pr-10 text-sm bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:border-rose-300 focus:ring-1 focus:ring-rose-300">
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.order.track') }}"
                        class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                        title="Lacak Pesanan">
                        <i class="bi bi-truck text-xl"></i>
                    </a>
                    @if(session('last_public_order_code'))
                        <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                            class="text-gray-600 hover:text-rose-600 p-2 rounded-full hover:bg-rose-50 transition-all duration-200"
                            title="Lihat Detail Pesanan">
                            <i class="bi bi-receipt text-xl"></i>
                        </a>
                    @endif
                    <a href="{{ route('public.cart.index') }}"
                        class="text-gray-600 hover:text-rose-600 relative p-2 rounded-full hover:bg-rose-50 transition-all duration-200">
                        <i class="bi bi-bag text-xl"></i>
                        {{-- <span --}}
                            {{-- class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center"></span> --}}
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-4 py-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Koleksi Bunga Premium</h2>
            <p class="text-gray-600 mb-2">Temukan bunga segar berkualitas tinggi untuk setiap momen spesial</p>
            <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                <i class="bi bi-clock text-rose-400"></i>
                Terakhir diperbarui: {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') : '-' }}
            </p>
        </div>
    </div>
    <!-- Main Navigation -->
    <nav class="flex justify-center space-x-8 py-4">
        <a href="?tab=flowers"
            class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 {{ $activeTab === 'flowers' ? 'text-rose-600' : 'text-gray-500 hover:text-gray-700' }}">
            🌸 Bunga
            @if($activeTab === 'flowers')
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-rose-400 to-pink-500 rounded-full">
                </div>
            @endif
        </a>
        <a href="?tab=bouquets"
            class="relative px-4 py-2 text-sm font-semibold transition-all duration-200 {{ $activeTab === 'bouquets' ? 'text-rose-600' : 'text-gray-500 hover:text-gray-700' }}">
            💐 Bouquet
            @if($activeTab === 'bouquets')
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-rose-400 to-pink-500 rounded-full">
                </div>
            @endif
        </a>
    </nav>
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
                    <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
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
                        <span class="mr-2">🌺</span>Semua
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                        data-category="Fresh Flowers" onclick="selectCategory(this)">
                        <span class="mr-2">🌿</span>Fresh Flowers
                    </button>
                    <button type="button"
                        class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                        data-category="Daun" onclick="selectCategory(this)">
                        <span class="mr-2">🍃</span>Daun
                    </button>
                </div>
            @else
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach($bouquetSizes as $size)
                        <button type="button"
                            class="chip-btn px-6 py-3 rounded-full border-2 border-rose-200 bg-white text-gray-700 text-sm font-semibold shadow-md hover:shadow-lg hover:bg-rose-50 transition-all duration-200"
                            data-size="{{ $size->id }}" onclick="selectSize(this)">
                            <span class="mr-2">📏</span>{{ $size->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @if($activeTab === 'flowers')
                @forelse($flowers as $flower)
                    <div class="flower-card group" data-category="{{ $flower->category->name ?? 'lainnya' }}"
                        data-name="{{ strtolower($flower->name) }}">
                        <div class="card-hover glass-effect rounded-2xl shadow-lg p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-40 mb-4 rounded-xl overflow-hidden">
                                @if($flower->image)
                                    <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl">
                                        <i class="bi bi-flower1 text-3xl text-rose-400"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <!-- Wishlist Button -->
                                <button class="absolute top-3 right-3 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <i class="bi bi-heart text-rose-500 text-sm"></i>
                                </button>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-bold text-sm text-gray-800 line-clamp-2 flex-1">{{ $flower->name }}</h3>
                                    <span class="ml-2 px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        {{ $flower->category->name ?? 'Umum' }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $flower->description }}</p>

                                <!-- Price -->
                                <div class="mb-3">
                                    @php
        $stemPrice = $flower->prices->firstWhere('type', 'per_tangkai');
                                    @endphp
                                    <div class="text-lg font-bold text-rose-600">
                                        Rp {{ number_format($stemPrice->price ?? 0, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">per tangkai</div>
                                </div>

                                <!-- Stock -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-gray-500">Stok:</span>
                                        <span class="font-semibold {{ $flower->current_stock > 10 ? 'text-green-600' : ($flower->current_stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $flower->current_stock }} tangkai
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-rose-400 to-pink-500 h-1.5 rounded-full transition-all duration-300" 
                                             style="width: {{ min(($flower->current_stock / 50) * 100, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <button onclick="openCartModal({{ $flower->id }})"
                                    class="mt-auto w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white text-xs font-semibold py-2.5 px-3 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <i class="bi bi-cart-plus mr-1"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-20 h-20 bg-rose-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="bi bi-flower1 text-2xl text-rose-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada bunga yang tersedia saat ini</h3>
                        <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                    </div>
                @endforelse
            @else
                @forelse($bouquets as $bouquet)
                    <div class="bouquet-card group" data-name="{{ strtolower($bouquet->name) }}"
                        data-sizes="{{ $bouquet->sizes->pluck('id')->join(',') }}">
                        <div class="card-hover glass-effect rounded-2xl shadow-lg p-4 h-full flex flex-col overflow-hidden">
                            <!-- Image -->
                            <div class="relative h-40 mb-4 rounded-xl overflow-hidden">
                                @if($bouquet->image)
                                    <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl">
                                        <i class="bi bi-flower3 text-3xl text-rose-400"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <!-- Wishlist Button -->
                                <button class="absolute top-3 right-3 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <i class="bi bi-heart text-rose-500 text-sm"></i>
                                </button>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-bold text-sm text-gray-800 line-clamp-2 flex-1">{{ $bouquet->name }}</h3>
                                    <span class="ml-2 px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        {{ $bouquet->category->name ?? 'Bouquet' }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $bouquet->description }}</p>

                                <!-- Sizes -->
                                <div class="mb-3">
                                    <span class="text-xs text-gray-500 block mb-1">Ukuran Tersedia:</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($bouquet->sizes as $size)
                                            <span class="inline-block px-2 py-0.5 bg-gradient-to-r from-rose-100 to-pink-100 text-rose-700 rounded-full text-[10px] font-medium">
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
                                    <div class="text-lg font-bold text-rose-600">
                                        @if($minPrice === $maxPrice)
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">rentang harga</div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('public.bouquet.detail', $bouquet->id) }}"
                                    class="mt-auto w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white text-xs font-semibold py-2.5 px-3 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg text-center block">
                                    <i class="bi bi-eye mr-1"></i>Lihat Detail
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
                        <p class="text-gray-500 text-sm">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                    </div>
                @endforelse
            @endif
        </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gradient-to-r from-rose-600 to-pink-600 text-white py-12 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                <span class="text-white font-bold text-2xl">F</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Fellie Florist</h3>
            <p class="text-rose-100 mb-4 max-w-2xl mx-auto">
                Menghadirkan keindahan bunga segar berkualitas premium untuk setiap momen berharga dalam hidup Anda
            </p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="#" class="text-rose-200 hover:text-white transition-colors">
                    <i class="bi bi-instagram text-xl"></i>
                </a>
                <a href="#" class="text-rose-200 hover:text-white transition-colors">
                    <i class="bi bi-facebook text-xl"></i>
                </a>
                <a href="#" class="text-rose-200 hover:text-white transition-colors">
                    <i class="bi bi-whatsapp text-xl"></i>
                </a>
            </div>
            <p class="text-rose-200 text-sm">© 2025 Fellie Florist. All rights reserved.</p>
        </div>
    </footer>

    <!-- Cart Modal -->
    @include('public.partials.cart-modal')

    <script>
        let selectedCategory = '';
        let selectedSize = '';

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
                    const sizes = card.getAttribute('data-sizes').split(',');
                    const matchSearch = name.includes(search);
                    const matchSize = !selectedSize || sizes.includes(selectedSize);
                    card.style.display = (matchSearch && matchSize) ? '' : 'none';
                    
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

        function selectSize(btn) {
            selectedSize = btn.getAttribute('data-size');
            document.querySelectorAll('.chip-btn').forEach(button => {
                button.classList.remove('bg-rose-500', 'text-white', 'border-rose-500');
                button.classList.add('bg-white', 'text-gray-700', 'border-rose-200');
            });
            btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500');
            btn.classList.remove('bg-white', 'text-gray-700', 'border-rose-200');
            filterItems();
        }

        // Initialize the first category button as active
        document.addEventListener('DOMContentLoaded', function () {
            const firstCategoryBtn = document.querySelector('.chip-btn');
            if (firstCategoryBtn) {
                selectCategory(firstCategoryBtn);
            }

            // Enhanced interactions
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                });
            });

            // Debounced search
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterItems, 300);
            });
        });
    </script>
</body>
</html>
