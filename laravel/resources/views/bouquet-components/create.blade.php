<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight mb-2">
            {{ __('Tambah Komponen Bouquet') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('bouquet-components.store') }}" method="POST" id="bouquetComponentForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bouquet</label>
                            <select name="bouquet_id" id="bouquet_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">- Pilih Buket -</option>
                                @foreach($bouquets as $bouquet)
                                    <option value="{{ $bouquet->id }}">{{ $bouquet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran</label>
                            <select name="size_id" id="size_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">- Pilih ukuran Buket -</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk</label>
                            <div class="flex flex-wrap gap-2 mb-2" id="categoryTabs">
                                @foreach($categories as $category)
                                    <button type="button"
                                        class="px-3 py-1 rounded border border-pink-500 text-pink-600 hover:bg-pink-50 category-tab"
                                        data-category="{{ $category->id }}">{{ $category->name }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4 flex flex-col md:flex-row gap-4 items-end">
                            <div class="w-full md:w-1/2">
                                <label class="block text-sm font-medium text-gray-700 mb-1"
                                    for="product_id">Produk</label>
                                <select id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">- Pilih Produk -</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                            data-price="{{ $product->default_price ?? 0 }}"
                                            data-type="{{ $product->price_type ?? '' }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-1/4">
                                <label class="block text-sm font-medium text-gray-700 mb-1"
                                    for="quantity">Jumlah</label>
                                <input type="number" min="1" id="quantity"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="1">
                            </div>
                            <div class="w-full md:w-1/4">
                                <button type="button"
                                    class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-4 rounded w-full"
                                    id="addProductBtn">Tambah Komponen</button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Komponen</label>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded" id="productTable">
                                    <thead>
                                        <tr class="bg-gray-100 text-gray-700">
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">
                                                Nama Bouquet</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">
                                                Ukuran</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">
                                                Produk</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">
                                                Jumlah</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Produk akan ditambahkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="components" id="productsInput">
                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('bouquet-components.index') }}"
                                class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit"
                                class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {
                    let productsData = [];

                    // Tab kategori produk
                    $(document).on('click', '.category-tab', function () {
                        $('.category-tab').removeClass('ring ring-pink-500 bg-pink-100');
                        $(this).addClass('ring ring-pink-500 bg-pink-100');
                        let catId = $(this).data('category');
                        // Filter produk di select
                        $('#product_id option').each(function () {
                            if (!catId || $(this).data('category') == catId) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                        $('#product_id').val('');
                    });

                    // Tambah produk ke tabel
                    $('#addProductBtn').on('click', function () {
                        console.log('Tombol Tambah Komponen diklik');
                        let productId = $('#product_id').val();
                        let productName = $('#product_id option:selected').text();
                        let quantity = parseInt($('#quantity').val()) || 1;
                        let bouquetId = $('#bouquet_id').val();
                        let bouquetName = $('#bouquet_id option:selected').text();
                        let sizeId = $('#size_id').val();
                        let sizeName = $('#size_id option:selected').text();
                        if (!bouquetId) { alert('Pilih Nama Bouquet terlebih dahulu!'); return; }
                        if (!sizeId) { alert('Pilih Ukuran terlebih dahulu!'); return; }
                        if (!productId) { alert('Pilih produk terlebih dahulu!'); return; }
                        if (productsData.find(p => p.product_id == productId && p.bouquet_id == bouquetId && p.size_id == sizeId)) {
                            alert('Produk dengan kombinasi Nama Bouquet dan Ukuran ini sudah ditambahkan!'); return;
                        }
                        productsData.push({
                            product_id: productId,
                            name: productName,
                            quantity: quantity,
                            bouquet_id: bouquetId,
                            bouquet_name: bouquetName,
                            size_id: sizeId,
                            size_name: sizeName
                        });
                        console.log('Data setelah tambah:', productsData);
                        renderTable();
                    });

                    // Hapus produk dari tabel
                    $('#productTable').on('click', '.remove-product', function () {
                        let idx = $(this).data('idx');
                        productsData.splice(idx, 1);
                        renderTable();
                    });

                    function renderTable() {
                        console.log('Render table dipanggil, data:', productsData);
                        let tbody = '';
                        productsData.forEach((p, i) => {
                            tbody += `<tr>
                                                                <td>${p.bouquet_name || '-'}</td>
                                                                <td>${p.size_name || '-'}</td>
                                                                <td>${p.name}</td>
                                                                <td>${p.quantity}</td>
                                                                <td><a href="#" class="text-pink-600 font-bold remove-product" data-idx="${i}">Hapus</a></td>
                                                            </tr>`;
                        });
                        $('#productTable tbody').html(tbody);
                        $('#productsInput').val(JSON.stringify(productsData));
                    }

                    // Submit form: validasi minimal 1 produk
                    $('#bouquetComponentForm').on('submit', function (e) {
                        if (productsData.length == 0) {
                            alert('Tambahkan minimal 1 produk!');
                            e.preventDefault();
                        }
                    });

                    // Set kategori tab pertama aktif saat load
                    $('.category-tab').first().trigger('click');
                });
            </script>
        @endpush

</x-app-layout>