<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan Publik - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8">
        <h2 class="text-lg font-semibold mb-4">Edit Pesanan Publik</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('public.order.update', ['public_code' => $order->public_code]) }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Nama</label>
                <input type="text" name="customer_name" class="border rounded w-full p-2"
                    value="{{ old('customer_name', $order->customer_name) }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Tanggal Ambil/Kirim</label>
                <input type="date" name="pickup_date" class="border rounded w-full p-2"
                    value="{{ old('pickup_date', $order->pickup_date) }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Waktu Ambil/Pengiriman</label>
                <input type="text" name="pickup_time" class="border rounded w-full p-2"
                    value="{{ old('pickup_time', $order->pickup_time) }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1">Metode Pengiriman</label>
                <input type="text" name="delivery_method" class="border rounded w-full p-2"
                    value="{{ old('delivery_method', $order->delivery_method) }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Tujuan Pengiriman</label>
                <input type="text" name="destination" class="border rounded w-full p-2"
                    value="{{ old('destination', $order->destination) }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1">No. WhatsApp</label>
                <input type="text" name="wa_number" class="border rounded w-full p-2"
                    value="{{ old('wa_number', $order->wa_number) }}">
            </div>

            <h3 class="text-md font-semibold mb-2 mt-6">Produk Dipesan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 mb-4" id="items-list">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-2 py-2 border">Nama Produk</th>
                            <th class="px-2 py-2 border">Tipe Harga</th>
                            <th class="px-2 py-2 border">Harga Satuan</th>
                            <th class="px-2 py-2 border">Satuan</th>
                            <th class="px-2 py-2 border">Jumlah</th>
                            <th class="px-2 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $i => $item)
                            <tr class="item-row">
                                <td class="border px-2 py-1">
                                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                    <select name="items[{{ $i }}][product_id]"
                                        class="border rounded p-1 w-full product-select" required data-row="{{ $i }}">
                                        <option value="">Pilih Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-prices='@json($product->prices)' {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border px-2 py-1">
                                    <select name="items[{{ $i }}][price_type]"
                                        class="border rounded p-1 w-full price-type-select" required data-row="{{ $i }}">
                                        <option value="">Pilih Tipe Harga</option>
                                        @if($item->product && $item->product->prices)
                                            @foreach($item->product->prices as $price)
                                                <option value="{{ $price->type }}" data-price="{{ $price->price }}"
                                                    data-unit="{{ $price->unit_equivalent }}" {{ $item->price_type == $price->type ? 'selected' : '' }}>{{ $price->type }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td class="border px-2 py-1">
                                    <input type="number" name="items[{{ $i }}][price]"
                                        class="border rounded p-1 w-full price-input" placeholder="Harga"
                                        value="{{ old('items.' . $i . '.price', $item->price) }}" min="0" readonly>
                                </td>
                                <td class="border px-2 py-1">
                                    <input type="number" name="items[{{ $i }}][unit_equivalent]"
                                        class="border rounded p-1 w-full unit-input" placeholder="Satuan"
                                        value="{{ old('items.' . $i . '.unit_equivalent', $item->unit_equivalent) }}" min="1"
                                        readonly>
                                </td>
                                <td class="border px-2 py-1">
                                    <input type="number" name="items[{{ $i }}][quantity]" class="border rounded p-1 w-full"
                                        placeholder="Jumlah" value="{{ old('items.' . $i . '.quantity', $item->quantity) }}"
                                        min="1">
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    <button type="button" class="text-red-600 font-bold remove-item">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" id="add-item"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded mb-4">+ Tambah
                Produk</button>

            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Simpan
                Perubahan</button>
            <a href="{{ route('public.order.invoice', ['public_code' => $order->public_code]) }}"
                class="ml-2 text-blue-600 hover:underline">Batal</a>
        </form>
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
        tr.className = 'item-row';
        tr.innerHTML = `
        <td class="border px-2 py-1">
            <select name="items[${idx}][product_id]" class="border rounded p-1 w-full product-select" required data-row="${idx}">
                ${optionsProduk}
            </select>
        </td>
        <td class="border px-2 py-1">
            <select name="items[${idx}][price_type]" class="border rounded p-1 w-full price-type-select" required data-row="${idx}">
                <option value="">Pilih Tipe Harga</option>
            </select>
        </td>
        <td class="border px-2 py-1">
            <input type="number" name="items[${idx}][price]" class="border rounded p-1 w-full price-input" placeholder="Harga" min="0" readonly>
        </td>
        <td class="border px-2 py-1">
            <input type="number" name="items[${idx}][unit_equivalent]" class="border rounded p-1 w-full unit-input" placeholder="Satuan" min="1" readonly>
        </td>
        <td class="border px-2 py-1">
            <input type="number" name="items[${idx}][quantity]" class="border rounded p-1 w-full" placeholder="Jumlah" min="1">
        </td>
        <td class="border px-2 py-1 text-center">
            <button type="button" class="text-red-600 font-bold remove-item">Hapus</button>
        </td>
    `;
        itemsTable.appendChild(tr);
    });

    // Dropdown dinamis: produk -> tipe harga -> harga/satuan
    document.getElementById('items-list').addEventListener('change', function (e) {
        // Jika produk berubah
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('.item-row');
            const idx = e.target.getAttribute('data-row');
            const priceTypeSelect = row.querySelector('.price-type-select');
            const priceInput = row.querySelector('.price-input');
            const unitInput = row.querySelector('.unit-input');
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            priceInput.value = '';
            unitInput.value = '';
            const selected = e.target.options[e.target.selectedIndex];
            let prices = [];
            try { prices = JSON.parse(selected.getAttribute('data-prices')); } catch { }
            prices.forEach(function (price) {
                priceTypeSelect.innerHTML += `<option value="${price.type}" data-price="${parseFloat(String(price.price).replace(/[,.]/g, '')) || 0}" data-unit="${price.unit_equivalent}">${price.type}</option>`;
            });
        }
        // Jika tipe harga berubah
        if (e.target.classList.contains('price-type-select')) {
            const row = e.target.closest('.item-row');
            const selected = e.target.options[e.target.selectedIndex];
            const priceInput = row.querySelector('.price-input');
            const unitInput = row.querySelector('.unit-input');
            priceInput.value = selected.getAttribute('data-price') || '';
            unitInput.value = selected.getAttribute('data-unit') || '';
        }
    });

    // Hapus produk
    document.getElementById('items-list').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>

</html>