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
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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
    </style>
</head>

<body class="min-h-screen bg-dark text-black flex flex-col items-center py-10 px-2 font-sans">
    <!-- Header -->
    <div class="max-w-4xl mx-auto text-center mb-10">
        <h2 class="text-2xl sm:text-3xl font-semibold text-pink">
            🌸 Katalog & Stok Bunga 🌸
        </h2>
        <h6
            class="mt-2 text-sm sm:text-3xl font-semibold text-pink-600 bg-pink-200/50 backdrop-blur-sm px-4 py-1 inline-block rounded-md shadow-sm">
            ~ Fellie Florist ~
        </h6>
        <br>
        <hr style="border: 0; border-top: 2px solid #fffffff6; width: 7%; margin: 8px auto;">
        <p class="mt-1 text-sm text-gray-500 flex items-center justify-center gap-1">
            <i class="bi bi-clock-history text-gray-400"></i>
            Terakhir diperbarui:
            <span class="text-gray-800 font-medium">
                {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') : '-' }}
            </span>
        </p>
        <hr style="border: 0; border-top: 2px solid #ffffff; width: 10%; margin: 8px auto;">

        <!-- Navigation Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 mt-4 mb-8 w-full max-w-2xl mx-auto">
            <a href="{{ route('public.cart.index') }}"
                class="min-w-[80px] max-w-[120px] bg-pink-500 hover:bg-pink-600 text-white font-bold px-2 py-1.5 rounded-md flex items-center justify-center text-xs shadow transition duration-200 focus:ring-2 focus:ring-pink-300 outline-none h-9 w-full sm:w-auto">
                <i class="bi bi-cart3 mr-1 text-sm"></i>Keranjang
            </a>
            @if(session('last_public_order_code'))
                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}"
                    class="min-w-[100px] max-w-[150px] bg-pink-500 hover:bg-pink-600 text-white font-bold px-2 py-1.5 rounded-md flex items-center justify-center text-xs shadow transition duration-200 focus:ring-2 focus:ring-pink-300 outline-none h-9 w-full sm:w-auto">
                    <i class="bi bi-receipt mr-1 text-sm"></i>Lihat Pesanan
                </a>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-6xl mx-auto">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex justify-center space-x-8" aria-label="Tabs">
                <a href="?tab=flowers"
                    class="px-3 py-2 text-sm font-medium {{ $activeTab === 'flowers' ? 'border-b-2 border-pink-500 text-pink-600' : 'text-gray-500 hover:text-gray-700' }}">
                    🌸 Bunga Satuan
                </a>
                <a href="?tab=bouquets"
                    class="px-3 py-2 text-sm font-medium {{ $activeTab === 'bouquets' ? 'border-b-2 border-pink-500 text-pink-600' : 'text-gray-500 hover:text-gray-700' }}">
                    💐 Bouquet
                </a>
            </nav>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 flex flex-col items-center">
            <div class="w-full max-w-md mb-4">
                <input type="text" id="searchInput"
                    placeholder="{{ $activeTab === 'flowers' ? 'Cari bunga...' : 'Cari bouquet...' }}"
                    class="w-full border border-gray-200 rounded-sm px-4 py-2 text-sm focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition"
                    oninput="filterItems()">
            </div>

            <!-- Dynamic Filters -->
            @if($activeTab === 'flowers')
                <div class="flex flex-wrap gap-2 justify-center">
                    <button type="button"
                        class="chip-btn px-4 py-1 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-semibold shadow-sm hover:bg-pink-100 focus:bg-pink-200 focus:text-pink-700 transition active"
                        data-category="" onclick="selectCategory(this)">Semua</button>
                    <button type="button"
                        class="chip-btn px-4 py-1 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-semibold shadow-sm hover:bg-pink-100 focus:bg-pink-200 focus:text-pink-700 transition"
                        data-category="Fresh Flowers" onclick="selectCategory(this)">Fresh Flowers</button>
                    <button type="button"
                        class="chip-btn px-4 py-1 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-semibold shadow-sm hover:bg-pink-100 focus:bg-pink-200 focus:text-pink-700 transition"
                        data-category="Daun" onclick="selectCategory(this)">Daun</button>
                </div>
            @else
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach($bouquetSizes as $size)
                        <button type="button"
                            class="chip-btn px-4 py-1 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-semibold shadow-sm hover:bg-pink-100 focus:bg-pink-200 focus:text-pink-700 transition"
                            data-size="{{ $size->id }}" onclick="selectSize(this)">
                            {{ $size->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
            @if($activeTab === 'flowers')
                @forelse($flowers as $flower)
                    <div class="flower-card" data-category="{{ $flower->category->name ?? 'lainnya' }}"
                        data-name="{{ strtolower($flower->name) }}">
                        <div class="relative bg-white rounded-lg shadow-lg p-4 h-full flex flex-col">
                            <!-- Image -->
                            <div class="relative h-40 mb-4">
                                @if($flower->image)
                                    <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}"
                                        class="w-full h-full object-cover rounded">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded">
                                        <i class="bi bi-flower1 text-3xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col">
                                <h3 class="font-semibold text-sm mb-2">{{ $flower->name }}</h3>
                                <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $flower->description }}</p>

                                <!-- Category -->
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500">Kategori:</span>
                                    <span class="text-xs font-medium">{{ $flower->category->name ?? 'Umum' }}</span>
                                </div>

                                <!-- Price -->
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500">Harga:</span>
                                    <div class="text-sm font-semibold text-green-600">
                                        @php
                                            $stemPrice = $flower->prices->firstWhere('type', 'per_tangkai');
                                        @endphp
                                        Rp {{ number_format($stemPrice->price ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Stock -->
                                <div class="mb-4">
                                    <span class="text-xs text-gray-500">Stok:</span>
                                    <span class="text-sm font-medium">{{ $flower->current_stock }} tangkai</span>
                                </div>

                                <!-- Action Button -->
                                <button onclick="openCartModal({{ $flower->id }})"
                                    class="mt-auto w-full bg-pink-500 hover:bg-pink-600 text-white text-xs font-semibold py-2 px-3 rounded transition">
                                    <i class="bi bi-cart-plus mr-1"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Tidak ada bunga yang tersedia saat ini.
                    </div>
                @endforelse
            @else
                @forelse($bouquets as $bouquet)
                    <div class="bouquet-card" data-name="{{ strtolower($bouquet->name) }}"
                        data-sizes="{{ $bouquet->sizes->pluck('id')->join(',') }}">
                        <div class="relative bg-white rounded-lg shadow-lg p-4 h-full flex flex-col">
                            <!-- Image -->
                            <div class="relative h-40 mb-4">
                                @if($bouquet->image)
                                    <img src="{{ asset('storage/' . $bouquet->image) }}" alt="{{ $bouquet->name }}"
                                        class="w-full h-full object-cover rounded">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded">
                                        <i class="bi bi-flower3 text-3xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col">
                                <h3 class="font-semibold text-sm mb-2">{{ $bouquet->name }}</h3>
                                <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $bouquet->description }}</p>

                                <!-- Category -->
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500">Kategori:</span>
                                    <span class="text-xs font-medium">{{ $bouquet->category->name ?? 'Bouquet' }}</span>
                                </div>

                                <!-- Sizes -->
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500">Ukuran Tersedia:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($bouquet->sizes as $size)
                                            <span
                                                class="inline-block px-2 py-0.5 bg-pink-100 text-pink-700 rounded-full text-[10px]">
                                                {{ $size->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Price Range -->
                                <div class="mb-4">
                                    <span class="text-xs text-gray-500">Rentang Harga:</span>
                                    <div class="text-sm font-semibold text-green-600">
                                        @php
                                            $minPrice = $bouquet->prices->min('price');
                                            $maxPrice = $bouquet->prices->max('price');
                                        @endphp
                                        @if($minPrice === $maxPrice)
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                            {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('public.bouquet.detail', $bouquet->id) }}"
                                    class="mt-auto w-full bg-pink-500 hover:bg-pink-600 text-white text-xs font-semibold py-2 px-3 rounded transition text-center">
                                    <i class="bi bi-eye mr-1"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Tidak ada bouquet yang tersedia saat ini.
                    </div>
                @endforelse
            @endif
        </div>
    </div>

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
                button.classList.remove('bg-pink-500', 'text-white');
                button.classList.add('bg-white', 'text-gray-700');
            });
            btn.classList.add('bg-pink-500', 'text-white');
            btn.classList.remove('bg-white', 'text-gray-700');
            filterItems();
        }

        function selectSize(btn) {
            selectedSize = btn.getAttribute('data-size');
            document.querySelectorAll('.chip-btn').forEach(button => {
                button.classList.remove('bg-pink-500', 'text-white');
                button.classList.add('bg-white', 'text-gray-700');
            });
            btn.classList.add('bg-pink-500', 'text-white');
            btn.classList.remove('bg-white', 'text-gray-700');
            filterItems();
        }

        // Initialize the first category button as active
        document.addEventListener('DOMContentLoaded', function () {
            const firstCategoryBtn = document.querySelector('.chip-btn');
            if (firstCategoryBtn) {
                selectCategory(firstCategoryBtn);
            }
        });
    </script>
</body>

</html>