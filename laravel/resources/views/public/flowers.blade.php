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

    <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center tracking-tight">
        <i class="bi bi-flower2 mr-2 text-pink-400"></i>Daftar Bunga Ready Stock
    </h1>

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
                    <button 
                        class="mt-3 w-full bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold py-2 rounded transition"
                        onclick="openOrderModal()"
                    >
                        <i class="bi bi-cart-plus mr-1"></i>Pesan Sekarang
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

</body>
<script>
    // Data produk dari backend (untuk dropdown)
    const allFlowers = @json($flowers);

    function openOrderModal() {
        document.getElementById('orderModal').classList.remove('hidden');
        document.getElementById('orderStep1').classList.remove('hidden');
        document.getElementById('orderStep2').classList.add('hidden');
        // Reset order items
        document.getElementById('orderItemsContainer').innerHTML = '';
        addOrderItemRow();
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
    function goToStep2(e) {
        e.preventDefault();
        document.getElementById('orderStep1').classList.add('hidden');
        document.getElementById('orderStep2').classList.remove('hidden');
        return false;
    }
    function addOrderItemRow() {
        const container = document.getElementById('orderItemsContainer');
        const idx = container.children.length;
        const row = document.createElement('div');
        row.className = 'flex gap-2 mb-2 items-end';
        row.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-semibold mb-1">Produk</label>
                <select name="product_id" class="w-full border rounded px-2 py-1 text-sm" onchange="updateProductInfo(this, ${idx})" required>
                    <option value="">Pilih Produk</option>
                    ${allFlowers.map(f => `<option value="${f.id}">${f.name}</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Tipe Harga</label>
                <select name="price_type" class="border rounded px-2 py-1 text-sm w-28" onchange="updatePriceByType(this, ${idx})" required disabled>
                    <option value="">Pilih Tipe</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Jumlah</label>
                <input type="number" name="quantity" min="1" class="border rounded px-2 py-1 text-sm w-20" required disabled>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Harga</label>
                <input type="text" name="price" class="border rounded px-2 py-1 text-sm w-24 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Satuan</label>
                <input type="text" name="unit_equivalent" class="border rounded px-2 py-1 text-sm w-16 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Stok</label>
                <input type="text" name="stock" class="border rounded px-2 py-1 text-sm w-16 bg-gray-100" readonly>
            </div>
            <button type="button" onclick="removeOrderItemRow(this)" class="text-red-500 hover:text-red-700 ml-1"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(row);
    }
    function removeOrderItemRow(btn) {
        btn.parentElement.remove();
    }
    function updateProductInfo(select, idx) {
        const productId = select.value;
        const flower = allFlowers.find(f => f.id == productId);
        const row = select.closest('div.flex');
        const priceTypeSelect = row.querySelector('select[name="price_type"]');
        const quantityInput = row.querySelector('input[name="quantity"]');
        if (flower) {
            // Populate price type dropdown
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe</option>' +
                (flower.prices || []).map(p => `<option value="${p.type}" data-price="${p.price}" data-unit="${p.unit_equivalent}">${p.type.replace('_', ' ')} - Rp${Number(p.price).toLocaleString('id-ID')}</option>`).join('');
            priceTypeSelect.disabled = false;
            quantityInput.disabled = false;
            row.querySelector('input[name="stock"]').value = flower.current_stock;
            row.querySelector('input[name="price"]').value = '';
            row.querySelector('input[name="unit_equivalent"]').value = '';
        } else {
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe</option>';
            priceTypeSelect.disabled = true;
            quantityInput.disabled = true;
            row.querySelector('input[name="stock"]').value = '-';
            row.querySelector('input[name="price"]').value = '-';
            row.querySelector('input[name="unit_equivalent"]').value = '-';
        }
    }

    function updatePriceByType(select, idx) {
        const row = select.closest('div.flex');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const unit = selectedOption.getAttribute('data-unit');
        row.querySelector('input[name="price"]').value = price ? 'Rp' + Number(price).toLocaleString('id-ID') : '-';
        row.querySelector('input[name="unit_equivalent"]').value = unit ? unit : '-';
    }
    function submitOrder(e) {
        e.preventDefault();
        // Ambil data dari form
        const customerName = document.querySelector('#orderStep1 [name="customer_name"]').value;
        const waNumber = document.querySelector('#orderStep1 [name="wa_number"]').value;
        const pickupDate = document.querySelector('#orderStep1 [name="pickup_date"]').value;
        const pickupTime = document.querySelector('#orderStep1 [name="pickup_time"]').value;
        const deliveryMethod = document.querySelector('#orderStep1 [name="delivery_method"]').value;
        const destination = document.querySelector('#orderStep1 [name="destination"]').value;

        // Ambil semua produk yang dipilih
        const items = [];
        let valid = true;
        document.querySelectorAll('#orderItemsContainer > div').forEach(row => {
            const productId = row.querySelector('select[name="product_id"]').value;
            const productName = row.querySelector('select[name="product_id"] option:checked').textContent;
            const priceType = row.querySelector('select[name="price_type"]').value;
            const quantity = row.querySelector('input[name="quantity"]').value;
            const unitEquivalent = row.querySelector('input[name="unit_equivalent"]').value;
            if (productId && priceType && quantity && unitEquivalent) {
                items.push({
                    product_id: productId,
                    product_name: productName,
                    price_type: priceType,
                    quantity: quantity,
                    unit_equivalent: unitEquivalent
                });
            } else {
                valid = false;
            }
        });
        if (!valid || items.length === 0) {
            alert('Lengkapi semua data produk!');
            return false;
        }
        // Siapkan data untuk dikirim
        const data = {
            customer_name: customerName,
            wa_number: waNumber,
            pickup_date: pickupDate,
            pickup_time: pickupTime,
            delivery_method: deliveryMethod,
            destination: destination,
            items: items
        };
        fetch('/public-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                alert('Pesanan berhasil dikirim!');
                closeOrderModal();
            } else {
                alert('Gagal mengirim pesanan: ' + (res.message || 'Terjadi kesalahan'));
            }
        })
        .catch(err => {
            alert('Gagal mengirim pesanan: ' + err.message);
        });
        return false;
    }
</script>
</html>
