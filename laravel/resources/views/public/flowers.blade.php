<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>🌸 Daftar Bunga Ready Stock</title>
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
<body class="min-h-screen bg-white text-black flex flex-col items-center py-10 px-2 font-sans">

    <div class="mt-2 text-sm text-gray-600 text-center">
        <i class="bi bi-clock-history mr-1"></i>Terakhir diperbarui: {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') : '-' }}
    </div>

    <div class="flex justify-between items-center mb-8 w-full max-w-6xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-center tracking-tight flex-1">
            <i class="bi bi-flower2 mr-2 text-pink-400"></i>Daftar Bunga Ready Stock
        </h1>
        <div class="flex items-center">
            <a href="{{ route('public.cart.index') }}" class="ml-4 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold px-4 py-2 rounded flex items-center text-sm">
                <i class="bi bi-cart3 mr-2"></i>Lihat Keranjang
            </a>
            @if(session('last_public_order_code'))
                <a href="{{ route('public.order.detail', ['public_code' => session('last_public_order_code')]) }}" class="ml-2 bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded flex items-center text-sm">
                    <i class="bi bi-receipt mr-1"></i>Lihat Detail Pemesanan
                </a>
            @endif
        </div>
    </div>

    <!-- Form Lacak Pesanan -->
    <div class="w-full max-w-2xl mx-auto mb-8">
        <form method="GET" action="{{ route('public.order.track') }}" class="flex flex-col sm:flex-row items-center gap-2 bg-gray-50 p-4 rounded shadow">
            <label class="text-sm font-semibold">Lacak Pesanan Anda:</label>
            <input type="text" name="wa_number" class="border rounded px-3 py-2 text-sm flex-1" placeholder="Masukkan No. WhatsApp" required>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Lacak</button>
        </form>
    </div>

    <div class="w-full max-w-6xl mx-auto">
        <div class="grid grid-cols-3 lg:grid-cols-4 gap-3">
            @forelse($flowers as $flower)
            <div class="bg-white shadow-lg rounded-sm hover:shadow-xl transition group flex flex-col overflow-hidden relative border border-gray-100 p-2 sm:p-3">
                <div class="relative h-28 sm:h-32 md:h-36 w-full overflow-hidden flex items-center justify-center bg-black rounded-sm">
                    @if($flower->image)
                        <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex items-center justify-center w-full h-full text-gray-400 text-3xl sm:text-4xl">
                            <i class="bi bi-flower2"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-60 px-2 py-1">
                        <span class="text-white font-semibold text-xs sm:text-sm truncate block">
                            <i class="bi bi-tag mr-1"></i>{{ $flower->name }}
                        </span>
                    </div>
                </div>
                <div class="flex-1 flex flex-col justify-between p-2 sm:p-3">
                    <div class="mb-1">
                        <span class="block text-[10px] text-gray-500"><i class="bi bi-bookmark mr-1"></i>Kategori</span>
                        <span class="block text-xs font-medium text-black">{{ $flower->category->name ?? '-' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="block text-[10px] text-gray-500"><i class="bi bi-card-text mr-1"></i>Deskripsi</span>
                        <span class="block text-xs text-black line-clamp-2">{{ $flower->description ?: '-' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="block text-[10px] text-gray-500"><i class="bi bi-currency-dollar mr-1"></i>Harga per Tangkai</span>
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
                    <div class="mb-1">
                        <span class="block text-[10px] text-gray-500"><i class="bi bi-currency-exchange mr-1"></i>Harga per Ikat</span>
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
                        <span class="text-[10px] text-gray-500"><i class="bi bi-box2-heart mr-1"></i>Stok Tersedia</span>
                        <span class="text-xs sm:text-sm font-bold text-black">{{ $flower->current_stock }} <span class="font-normal">Tangkai</span></span>
                    </div>
                    <button type="button" onclick="openCartModal({{ $flower->id }})" class="w-full bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold py-2 rounded transition">
                        <i class="bi bi-cart-plus mr-1"></i>Tambah ke Keranjang
                    </button>
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
    // Data produk dari backend (untuk dropdown)
    const allFlowers = @json($flowers);
    let selectedFlower = null;

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
