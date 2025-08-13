<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan Publik - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .btn-primary {
            background-color: #ec4899;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #be185d;
        }
        .btn-secondary {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background-color: #2563eb;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8 px-4 max-w-6xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <div class="bg-pink-100 p-3 rounded-lg mr-4">
                    <i class="bi bi-pencil-square text-pink-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Pesanan Publik</h2>
                    <p class="text-gray-600">Kode Pesanan: {{ $order->public_code }}</p>
                </div>
            </div>
        <h2 class="text-lg font-semibold mb-4">Edit Pesanan Publik</h2>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('public.order.update', ['public_code' => $order->public_code]) }}"
              class="space-y-6">
            @csrf
            
            <!-- Customer Information Section -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-person-circle mr-2"></i>Informasi Pelanggan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="customer_name" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                               value="{{ old('customer_name', $order->customer_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp</label>
                        <input type="text" name="wa_number" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                               value="{{ old('wa_number', $order->wa_number) }}" 
                               placeholder="Contoh: 08123456789">
                    </div>
                </div>
            </div>

            <!-- Delivery Information Section -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-truck mr-2"></i>Informasi Pengiriman
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Ambil/Kirim *</label>
                        <input type="date" name="pickup_date" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                               value="{{ old('pickup_date', $order->pickup_date) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Ambil/Pengiriman</label>
                        <input type="text" name="pickup_time" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                               value="{{ old('pickup_time', $order->pickup_time) }}" 
                               placeholder="Contoh: 09:00 - 12:00">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pengiriman *</label>
                        <select name="delivery_method" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                            <option value="">Pilih Metode Pengiriman</option>
                            <option value="Ambil Sendiri" {{ old('delivery_method', $order->delivery_method) == 'Ambil Sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                            <option value="Diantar Gratis" {{ old('delivery_method', $order->delivery_method) == 'Diantar Gratis' ? 'selected' : '' }}>Diantar Gratis</option>
                            <option value="Gosend (Pesan Dari Toko)" {{ old('delivery_method', $order->delivery_method) == 'Gosend (Pesan Dari Toko)' ? 'selected' : '' }}>Gosend (Pesan Dari Toko)</option>
                            <option value="Gocar (Pesan Dari Toko)" {{ old('delivery_method', $order->delivery_method) == 'Gocar (Pesan Dari Toko)' ? 'selected' : '' }}>Gocar (Pesan Dari Toko)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Tujuan</label>
                        <input type="text" name="destination" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                               value="{{ old('destination', $order->destination) }}" 
                               placeholder="Alamat lengkap untuk pengiriman">
                    </div>
                </div>
                <div class="form-group mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" 
                              rows="3" 
                              placeholder="Masukkan catatan khusus untuk pesanan (opsional)">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-box-seam mr-2"></i>Produk Dipesan
                    </h3>
                    <button type="button" id="add-item"
                            class="btn-secondary inline-flex items-center">
                        <i class="bi bi-plus-lg mr-2"></i>Tambah Produk
                    </button>
                </div>
                
                <div class="table-container bg-white rounded-lg border border-gray-200">
                    <table class="min-w-full" id="items-list">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nama Produk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Tipe Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Harga Satuan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Satuan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Jumlah</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $i => $item)
                                <tr class="item-row hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                        <select name="items[{{ $i }}][product_id]"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 product-select" 
                                                required data-row="{{ $i }}">
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-prices='@json($product->prices)' 
                                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <select name="items[{{ $i }}][price_type]"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 price-type-select" 
                                                required data-row="{{ $i }}">
                                            <option value="">Pilih Tipe Harga</option>
                                            @if($item->product && $item->product->prices)
                                                @foreach($item->product->prices as $price)
                                                    <option value="{{ $price->type }}" 
                                                            data-price="{{ $price->price }}"
                                                            data-unit="{{ $price->unit_equivalent }}" 
                                                            {{ $item->price_type == $price->type ? 'selected' : '' }}>
                                                        {{ $price->type }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold">
                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $i }}][unit_equivalent]"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 unit-input" 
                                               placeholder="Satuan"
                                               value="{{ old('items.' . $i . '.unit_equivalent', $item->unit_equivalent) }}" 
                                               min="1" readonly required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $i }}][quantity]" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                               placeholder="Jumlah" 
                                               value="{{ old('items.' . $i . '.quantity', $item->quantity) }}"
                                               min="1" required>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-800 font-medium remove-item">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan Pembayaran -->
            @php
                $totalOrder = $order->items->sum(function ($item) {
                    return ($item->price ?? 0) * ($item->quantity ?? 0);
                });
                $shippingFee = $order->shipping_fee ?? 0;
                $grandTotal = $totalOrder + $shippingFee;
                $totalPaid = $order->amount_paid ?? 0;
                $sisaPembayaran = $order->payment_status === 'paid' ? 0 : max($grandTotal - $totalPaid, 0);
            @endphp
            <div class="bg-gradient-to-r from-pink-50 to-red-50 border border-pink-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-1">Total Produk</div>
                        <div class="text-2xl font-bold text-green-600">Rp{{ number_format($totalOrder, 0, ',', '.') }}</div>
                    </div>
                    @if($shippingFee > 0)
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-1">Ongkir</div>
                        <div class="text-xl font-bold text-orange-600">Rp{{ number_format($shippingFee, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-1">Sudah Dibayar</div>
                        <div class="text-2xl font-bold text-blue-600">Rp{{ number_format($totalPaid, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-1">Sisa Pembayaran</div>
                        <div class="text-2xl font-bold text-red-600">Rp{{ number_format($sisaPembayaran, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <!-- Submit Section -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6">
                <a href="{{ route('public.order.invoice', ['public_code' => $order->public_code]) }}"
                   class="text-gray-600 hover:text-gray-800 font-medium inline-flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>Kembali ke Invoice
                </a>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary inline-flex items-center">
                        <i class="bi bi-check-lg mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

        <!-- Modal Konfirmasi Hapus Item -->
        <div id="deleteItemModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <i class="bi bi-trash text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus Produk</h3>
                </div>
                <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus produk ini dari pesanan?</p>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelDeleteBtn" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
                    <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>

</body>
<script>
    // Data produk dan harga dari backend
    const productsData = @json($products);

    // Tambah produk baru
    document.getElementById('add-item').addEventListener('click', function () {
        const itemsTable = document.getElementById('items-list').getElementsByTagName('tbody')[0];
        const idx = itemsTable.querySelectorAll('.item-row').length;
        let optionsProduk = '<option value="">Pilih Produk</option>';
        productsData.forEach(function (prod) {
            optionsProduk += `<option value="${prod.id}" data-prices='${JSON.stringify(prod.prices)}'>${prod.name}</option>`;
        });
        const tr = document.createElement('tr');
        tr.className = 'item-row hover:bg-gray-50';
        tr.innerHTML = `
        <td class="px-4 py-3">
            <select name="items[${idx}][product_id]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 product-select" required data-row="${idx}">
                ${optionsProduk}
            </select>
        </td>
        <td class="px-4 py-3">
            <select name="items[${idx}][price_type]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 price-type-select" required data-row="${idx}">
                <option value="">Pilih Tipe Harga</option>
            </select>
        </td>
        <td class="px-4 py-3">
            <span class="price-view text-right font-semibold block">-</span>
            <input type="hidden" name="items[${idx}][price]" class="price-input" required>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="items[${idx}][unit_equivalent]" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 unit-input" placeholder="Satuan" min="1" readonly>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="items[${idx}][quantity]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="Jumlah" min="1" required>
        </td>
        <td class="px-4 py-3 text-center">
            <button type="button" class="text-red-600 hover:text-red-800 font-medium remove-item">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </td>
    `;
        itemsTable.appendChild(tr);
    });
        // Format ribuan pada input harga
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^\d]/g, ''),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);
            if (ribuan) {
                rupiah += (sisa ? '.' : '') + ribuan.join('.');
            }
            return rupiah;
        }

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('price-input')) {
                let val = e.target.value.replace(/[^\d]/g, '');
                if (val) {
                    e.target.value = formatRupiah(val);
                } else {
                    e.target.value = '';
                }
            }
        });

    // Dropdown dinamis: produk -> tipe harga -> harga/satuan
    document.getElementById('items-list').addEventListener('change', function (e) {
        // Jika produk berubah
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('.item-row');
            const idx = e.target.getAttribute('data-row');
            const priceTypeSelect = row.querySelector('.price-type-select');
            const priceInput = row.querySelector('.price-input');
            const priceView = row.querySelector('.price-view');
            const unitInput = row.querySelector('.unit-input');

            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            priceInput.value = '';
            priceView.textContent = '-';
            unitInput.value = '';

            const selected = e.target.options[e.target.selectedIndex];
            let prices = [];
            try {
                const pricesData = selected.getAttribute('data-prices');
                if (pricesData) {
                    prices = JSON.parse(pricesData);
                }
            } catch (e) {
                console.error('Error parsing prices data:', e);
            }

            prices.forEach(function (price) {
                // Format harga ke ribuan
                const priceValue = parseInt(String(price.price).replace(/[^\d]/g, '')) || 0;
                const formattedPrice = formatRupiah(priceValue.toString());
                priceTypeSelect.innerHTML += `<option value="${price.type}" data-price="${priceValue}" data-unit="${price.unit_equivalent}">${price.type}</option>`;
            });
        }

        // Jika tipe harga berubah
        if (e.target.classList.contains('price-type-select')) {
            const row = e.target.closest('.item-row');
            const selected = e.target.options[e.target.selectedIndex];
            const priceInput = row.querySelector('.price-input');
            const priceView = row.querySelector('.price-view');
            const unitInput = row.querySelector('.unit-input');
            // Ambil harga mentah dari data-price (integer)
            let raw = selected.getAttribute('data-price') || '';
            priceInput.value = raw;
            priceView.textContent = raw ? `Rp${formatRupiah(raw)}` : '-';
            unitInput.value = selected.getAttribute('data-unit') || '';
        }
    });

    // Modal konfirmasi hapus item
    let itemRowToDelete = null;
    const deleteItemModal = document.getElementById('deleteItemModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    document.getElementById('items-list').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            const row = e.target.closest('.item-row');
            const itemsTable = document.getElementById('items-list').getElementsByTagName('tbody')[0];
            if (itemsTable.querySelectorAll('.item-row').length > 1) {
                itemRowToDelete = row;
                deleteItemModal.classList.remove('hidden');
            } else {
                showNotification('Minimal harus ada satu produk dalam pesanan.', 'error');
            }
        }
    });

    cancelDeleteBtn.addEventListener('click', function () {
        deleteItemModal.classList.add('hidden');
        itemRowToDelete = null;
    });

    confirmDeleteBtn.addEventListener('click', function () {
        if (itemRowToDelete) {
            itemRowToDelete.remove();
            updateItemIndexes();
        }
        deleteItemModal.classList.add('hidden');
        itemRowToDelete = null;
    });

    // Notification function (simple toast)
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300`;
        if (type === 'error') {
            notification.className += ' bg-red-500 text-white';
        } else if (type === 'success') {
            notification.className += ' bg-green-500 text-white';
        } else {
            notification.className += ' bg-blue-500 text-white';
        }
        notification.innerHTML = `<div class="flex items-center">${message}<button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200"><i class="bi bi-x"></i></button></div>`;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.classList.add('opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Update indexes setelah penghapusan item
    function updateItemIndexes() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            // Update name attributes untuk semua input dalam row
            const inputs = row.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name && name.includes('items[')) {
                    const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                    input.setAttribute('name', newName);
                }
                // Update data-row attributes
                if (input.hasAttribute('data-row')) {
                    input.setAttribute('data-row', index);
                }
            });
        });
    }

    // Form validation sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.item-row');
        let isValid = true;
        let errorMessage = '';

        if (rows.length === 0) {
            isValid = false;
            errorMessage = 'Minimal harus ada satu produk dalam pesanan.';
        }

        rows.forEach((row, index) => {
            const productSelect = row.querySelector('.product-select');
            const priceTypeSelect = row.querySelector('.price-type-select');
            const priceInput = row.querySelector('.price-input');
            const priceView = row.querySelector('.price-view');
            const quantityInput = row.querySelector('input[name*="[quantity]"]');

            // Pastikan input price selalu ada dan terisi angka mentah
            if (priceInput) {
                // Jika belum ada value, coba ambil dari priceView
                if (!priceInput.value && priceView) {
                    let raw = priceView.textContent.replace(/[^\d]/g, '');
                    priceInput.value = raw;
                }
            }

            if (!productSelect.value) {
                isValid = false;
                errorMessage = `Produk pada baris ${index + 1} harus dipilih.`;
            }
            if (!priceTypeSelect.value) {
                isValid = false;
                errorMessage = `Tipe harga pada baris ${index + 1} harus dipilih.`;
            }
            if (!priceInput || !priceInput.value || parseFloat(priceInput.value) <= 0) {
                isValid = false;
                errorMessage = `Harga pada baris ${index + 1} harus lebih dari 0.`;
            }
            if (!quantityInput.value || parseInt(quantityInput.value) <= 0) {
                isValid = false;
                errorMessage = `Jumlah pada baris ${index + 1} harus lebih dari 0.`;
            }
        });

        if (!isValid) {
            e.preventDefault();
            showNotification(errorMessage, 'error');
        }
    });
</script>

</html>