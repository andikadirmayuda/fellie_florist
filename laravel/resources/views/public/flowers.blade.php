<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>🌸 Daftar Bunga Ready Stock Fellie Florist</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Figtree Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />

    <!-- Tailwind CSS CDN (pastikan sudah di-include) -->
    <script src="https://cdn.tailwindcss.com"></script>

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
    </style>
</head>
<body class="min-h-screen bg-dark text-black flex flex-col items-center py-10 px-2 font-sans">

    
    
    <div class="max-w-4xl mx-auto text-center mb-10">
        <h2 class="text-2xl sm:text-3xl font-semibold text-pink">
            🌸Katalog & Stok Bunga🌸
        </h2>
        <h6 class="mt-2 text-sm sm:text-3xl font-semibold text-pink-600 bg-pink-200/50 backdrop-blur-sm px-4 py-1 inline-block rounded-md shadow-sm">
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
            <form method="GET" action="{{ route('public.order.track') }}"
                  class="flex flex-row items-center gap-1 bg-pink-50 border border-pink-200 p-2 rounded-lg shadow-sm hover:shadow-md transition-all w-full max-w-xs sm:max-w-[340px]">
                <label for="wa_number" class="text-xs font-semibold text-pink-700 flex items-center gap-1 mb-0">
                    <i class="bi bi-search text-base"></i>
                </label>
                <input type="text" name="wa_number" id="wa_number"
                       class="border border-pink-200 rounded px-3 py-1 text-xs w-full sm:w-[140px] focus:ring-2 focus:ring-pink-300 focus:outline-none"
                       placeholder="08xxxx" required aria-label="Nomor WhatsApp">
                <button type="submit"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-bold px-2 py-1 rounded-md flex items-center gap-1 text-xs focus:ring-2 focus:ring-pink-300 outline-none w-auto">
                    <i class="bi bi-arrow-right-circle"></i>Lacak
                </button>
            </form>
        </div>
        {{-- <span class="block w-full text-[10px] text-pink-700 mt-1 text-center leading-tight pb-1">Masukkan nomor WhatsApp yang digunakan saat memesan.</span> --}}
    </div>




    <!-- Filter & Search Bar (centered under last updated) -->
    <div class="w-full max-w-6xl mx-auto mb-6 flex flex-col items-center">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4 w-full max-w-md">
            <input id="flowerSearch" type="text" placeholder="Cari bunga..." class="w-full border border-gray-200 rounded-sm px-4 py-2 text-sm focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition" oninput="filterFlowers()">
        </div>
        <div id="categoryChips" class="flex flex-wrap gap-2 justify-center mb-2"></div>
    </div>

        <div id="flowersGrid" class="grid grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
            @forelse($flowers as $flower)
            <div class="flower-card relative group flex flex-col h-full" data-name="{{ strtolower($flower->name) }}" data-category="{{ $flower->category->name ?? 'lainnya' }}">
                <div class="absolute -inset-1 bg-white/60 rounded-sm blur-xl opacity-70 group-hover:opacity-90 transition-all duration-300 z-0"></div>
                <div class="relative z-10 bg-white/80 backdrop-blur-lg border border-gray-100 rounded-sm shadow-xl hover:shadow-2xl ring-1 ring-gray-100 transition-all duration-300 p-3 sm:p-4 md:p-6 flex flex-col h-full min-h-[340px] sm:min-h-[380px] md:min-h-[420px] w-full">
                    <div class="relative h-28 sm:h-32 md:h-36 lg:h-40 w-full overflow-hidden flex items-center justify-center bg-black rounded-sm mb-3">
                        @if($flower->image)
                            <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300 max-h-40 aspect-square">
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-400 text-2xl xs:text-3xl sm:text-4xl">
                                <i class="bi bi-flower2"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-60 px-2 py-1 rounded-b-xl">
                            <span class="text-white font-semibold text-xs sm:text-sm truncate block">
                                <i class="bi bi-tag mr-1"></i>{{ $flower->name }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col justify-between w-full mt-2">
                        <div class="mb-2">
                            <span class="block text-[11px] text-gray-500"><i class="bi bi-bookmark mr-1"></i>Kategori</span>
                            <span class="block text-xs font-medium text-black">{{ $flower->category->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="block text-[11px] text-gray-500"><i class="bi bi-card-text mr-1"></i>Deskripsi</span>
                            <span class="block text-xs text-black line-clamp-2 min-h-[32px]">{{ $flower->description ?: '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="block text-[11px] text-gray-500"><i class="bi bi-currency-dollar mr-1"></i>Harga per Tangkai</span>
                            <span class="block text-xs font-semibold text-green-700">
                                @php
                                    $stemPrice = $flower->prices->firstWhere('type', 'per_tangkai');
                                @endphp
                                @if($stemPrice)
                                    Rp{{ number_format($stemPrice->price,0,',','.') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="block text-[11px] text-gray-500"><i class="bi bi-currency-exchange mr-1"></i>Harga per Ikat</span>
                            <span class="block text-xs font-semibold text-blue-700">
                                @php
                                    $bundlePrice = $flower->prices->firstWhere('type', 'ikat_20')
                                        ?? $flower->prices->firstWhere('type', 'ikat_10')
                                        ?? $flower->prices->firstWhere('type', 'ikat_5');
                                @endphp
                                @if($bundlePrice)
                                    Rp{{ number_format($bundlePrice->price,0,',','.') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-[11px] text-gray-500"><i class="bi bi-box2-heart mr-1"></i>Stok Tersedia</span>
                            <span class="text-xs sm:text-sm font-bold text-black">{{ $flower->current_stock }} <span class="font-normal">Tangkai</span></span>
                        </div>
                        <button  class="mt-3 w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 rounded transition"
                        onclick="openCartModal({{ $flower->id }})">
                        <i class="bi bi-cart-plus mr-1"></i>Tambah ke Keranjang</button>                
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8">Tidak ada bunga ready stock.</div>
            @endforelse
        </div>

    <!-- Modal Tahap 1: Data Pelanggan -->
    <div id="orderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="closeOrderModal()" class="absolute top-2 right-2 text-gray-400 hover:text-black"><i class="bi bi-x-lg"></i></button>
            <h2 class="text-lg font-bold mb-4">Form Pemesanan</h2>
            <form id="orderStep1" onsubmit="return goToStep2(event)">
                <input type="hidden" id="selectedFlowerId" name="flower_id">
                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1">Nama Pelanggan</label>
                    <input type="text" name="customer_name" class="w-full border rounded px-3 py-2 text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1">No. WhatsApp</label>
                    <input type="text" name="wa_number" class="w-full border rounded px-3 py-2 text-sm" placeholder="08xxxxxxxxxx" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1">Tanggal Diambil/Dikirim</label>
                    <input type="date" name="pickup_date" class="w-full border rounded px-3 py-2 text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1">Waktu Ambil/Pengiriman</label>
                    <input type="time" name="pickup_time" class="w-full border rounded px-3 py-2 text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1">Metode Pengiriman</label>
                    <select name="delivery_method" class="w-full border rounded px-3 py-2 text-sm" required>
                        <option value="Ambil Langsung">Ambil Langsung</option>
                        <option value="GoSend">GoSend</option>
                        <option value="Kurir Toko">Kurir Toko</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1">Tujuan Pengiriman</label>
                    <textarea name="destination" rows="3" class="w-full border rounded px-3 py-2 text-sm resize-y" placeholder="Alamat atau tujuan pengiriman" required></textarea>
                </div>
                <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 rounded mt-2">Lanjut Pilih Produk</button>
            </form>

            <!-- Step 2: Pilih Produk & Jumlah -->
            <form id="orderStep2" class="hidden" onsubmit="return submitOrder(event)">
                <h3 class="text-base font-semibold mb-2">Pilih Produk & Jumlah</h3>
                <div id="orderItemsContainer"></div>
                <button type="button" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded mb-2" onclick="addOrderItemRow()">
                    <i class="bi bi-plus-circle mr-1"></i>Tambah Produk
                </button>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded mt-2">Kirim Pesanan</button>
            </form>
        </div>
    </div>


<!-- Modal Tambah ke Keranjang -->
<div id="cartModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xs p-6 relative">
        <button onclick="closeCartModal()" class="absolute top-2 right-2 text-gray-400 hover:text-black"><i class="bi bi-x-lg"></i></button>
        <h2 class="text-lg font-bold mb-4">Tambah ke Keranjang</h2>
        <form id="cartAddForm" method="POST" action="{{ route('public.cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" id="cartProductId">
            <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Tipe Harga</label>
                <select name="price_type" id="cartPriceType" class="w-full border rounded px-3 py-2 text-sm" required></select>
            </div>
            <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Jumlah</label>
                <input type="number" name="quantity" id="cartQuantity" class="w-full border rounded px-3 py-2 text-sm" min="1" value="1" required>
            </div>
            <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Harga Satuan</label>
                <input type="text" id="cartUnitPrice" class="w-full border rounded px-3 py-2 text-sm bg-gray-100" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-xs font-semibold mb-1">Total Harga</label>
                <input type="text" id="cartTotalPrice" class="w-full border rounded px-3 py-2 text-sm bg-gray-100" readonly>
            </div>
            <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 rounded mt-2">Tambah ke Keranjang</button>
        </form>
    </div>
</div>

<script>
    // Data produk dari backend (untuk dropdown & filter)
    const allFlowers = @json($flowers);
    let selectedFlower = null;

    // --- FILTER & SEARCH ---
    // Ambil semua kategori unik
    const categories = Array.from(new Set(allFlowers.map(f => f.category?.name || 'Lainnya')));
    const categoryChips = document.getElementById('categoryChips');
    let selectedCategory = '';

    function renderCategoryChips() {
        let html = `<button type="button" class="chip-btn px-4 py-1 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-semibold shadow-sm hover:bg-pink-100 focus:bg-pink-200 focus:text-pink-700 transition" onclick="selectCategory('')">Semua</button>`;
        categories.forEach(cat => {
            html += `<button type="button" class="chip-btn px-4 py-1 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-semibold shadow-sm hover:bg-pink-100 focus:bg-pink-200 focus:text-pink-700 transition" onclick="selectCategory('${cat.replace(/'/g, '\'')}')">${cat}</button>`;
        });
        categoryChips.innerHTML = html;
    }

    function selectCategory(cat) {
        selectedCategory = cat;
        // Highlight chip
        document.querySelectorAll('.chip-btn').forEach(btn => {
            btn.classList.remove('bg-pink-500', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700');
            if (btn.textContent === cat || (cat === '' && btn.textContent === 'Semua')) {
                btn.classList.add('bg-pink-500', 'text-white');
                btn.classList.remove('bg-white', 'text-gray-700');
            }
        });
        filterFlowers();
    }

    function filterFlowers() {
        const search = document.getElementById('flowerSearch').value.toLowerCase();
        document.querySelectorAll('.flower-card').forEach(card => {
            const name = card.getAttribute('data-name');
            const category = card.getAttribute('data-category');
            const matchSearch = name.includes(search);
            const matchCategory = !selectedCategory || category === selectedCategory;
            card.style.display = (matchSearch && matchCategory) ? '' : 'none';
        });
    }

    // Render chips & set default
    renderCategoryChips();
    selectCategory('');

    // --- CART MODAL ---
    function openCartModal(flowerId) {
        selectedFlower = allFlowers.find(f => f.id === flowerId);
        if (!selectedFlower) return;
        document.getElementById('cartModal').classList.remove('hidden');
        document.getElementById('cartProductId').value = selectedFlower.id;
        // Populate price type
        const priceTypeSelect = document.getElementById('cartPriceType');
        priceTypeSelect.innerHTML = '';
        (selectedFlower.prices || []).forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.type;
            opt.textContent = `${p.type.replace('_',' ')} - Rp${Number(p.price).toLocaleString('id-ID')}`;
            opt.setAttribute('data-price', p.price);
            priceTypeSelect.appendChild(opt);
        });
        priceTypeSelect.selectedIndex = 0;
        document.getElementById('cartQuantity').value = 1;
        updateCartModalPrice();
    }
    function closeCartModal() {
        document.getElementById('cartModal').classList.add('hidden');
    }
    document.getElementById('cartPriceType').addEventListener('change', updateCartModalPrice);
    document.getElementById('cartQuantity').addEventListener('input', updateCartModalPrice);
    function updateCartModalPrice() {
        const priceTypeSelect = document.getElementById('cartPriceType');
        const selectedOption = priceTypeSelect.options[priceTypeSelect.selectedIndex];
        const unitPrice = selectedOption ? Number(selectedOption.getAttribute('data-price')) : 0;
        const qty = Number(document.getElementById('cartQuantity').value) || 1;
        document.getElementById('cartUnitPrice').value = unitPrice ? 'Rp' + unitPrice.toLocaleString('id-ID') : '-';
        document.getElementById('cartTotalPrice').value = unitPrice ? 'Rp' + (unitPrice * qty).toLocaleString('id-ID') : '-';
    }
    // ...existing code modal order...
</script>
</html>
