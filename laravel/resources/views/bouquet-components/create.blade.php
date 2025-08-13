<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-pink-700">Tambah Komponen Bouquet</h2>
            <a href="{{ route('bouquet-components.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
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
                            <div class="relative">
                                <button type="button" id="bouquetDropdownButton"
                                    class="w-full px-3 py-2.5 text-left bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200">
                                    <span class="text-gray-500">- Pilih Buket -</span>
                                </button>
                                <div id="bouquetDropdownPanel"
                                    class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                                    <div class="p-3">
                                        <!-- Search input -->
                                        <div class="mb-3">
                                            <input type="text" id="bouquetSearch"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                                placeholder="Cari bouquet...">
                                        </div>
                                        <!-- Grid of bouquets -->
                                        <div class="max-h-60 overflow-y-auto">
                                            <div class="grid grid-cols-2 gap-2" id="bouquetsGrid">
                                                @foreach($bouquets as $bouquet)
                                                    <button type="button"
                                                        class="bouquet-option text-left px-3 py-2 rounded-md hover:bg-pink-50 transition-colors"
                                                        data-bouquet-id="{{ $bouquet->id }}"
                                                        data-name="{{ strtolower($bouquet->name) }}"
                                                        data-original-name="{{ $bouquet->name }}">
                                                        {{ $bouquet->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="bouquet_id" id="bouquet_id" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran</label>
                            <div class="relative">
                                <button type="button" id="sizeDropdownButton"
                                    class="w-full px-3 py-2.5 text-left bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200">
                                    <span class="text-gray-500">- Pilih ukuran Buket -</span>
                                </button>
                                <div id="sizeDropdownPanel"
                                    class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                                    <div class="p-3">
                                        <!-- Search input -->
                                        <div class="mb-3">
                                            <input type="text" id="sizeSearch"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                                placeholder="Cari ukuran...">
                                        </div>
                                        <!-- Grid of sizes -->
                                        <div class="max-h-48 overflow-y-auto">
                                            <div class="grid grid-cols-1 gap-1" id="sizesGrid">
                                                @foreach($sizes as $size)
                                                    <button type="button"
                                                        class="size-option text-left px-3 py-2 rounded-md hover:bg-pink-50 transition-colors"
                                                        data-size-id="{{ $size->id }}"
                                                        data-name="{{ strtolower($size->name) }}"
                                                        data-original-name="{{ $size->name }}">
                                                        {{ $size->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="size_id" id="size_id" required>
                            </div>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                                <div class="relative">
                                    <button type="button" id="productDropdownButton"
                                        class="w-full px-3 py-2.5 text-left bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200">
                                        <span class="text-gray-500">- Pilih Produk -</span>
                                    </button>
                                    <div id="productDropdownPanel"
                                        class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                                        <div class="p-3">
                                            <!-- Search input -->
                                            <div class="mb-3">
                                                <input type="text" id="productSearch"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                                    placeholder="Cari produk...">
                                            </div>
                                            <!-- Grid of products -->
                                            <div class="max-h-60 overflow-y-auto">
                                                <div class="grid grid-cols-2 gap-2" id="productsGrid">
                                                    @foreach($products as $product)
                                                        <button type="button"
                                                            class="product-option text-left px-3 py-2 rounded-md hover:bg-pink-50 transition-colors"
                                                            data-product-id="{{ $product->id }}"
                                                            data-category="{{ $product->category_id }}"
                                                            data-name="{{ strtolower($product->name) }}"
                                                            data-price="{{ $product->default_price ?? 0 }}"
                                                            data-price-type="{{ $product->price_type ?? '' }}"
                                                            data-original-name="{{ $product->name }}">
                                                            {{ $product->name }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="product_id" value="">
                                </div>
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

                    // Custom Bouquet Dropdown
                    const $dropdownButton = $('#bouquetDropdownButton');
                    const $dropdownPanel = $('#bouquetDropdownPanel');
                    const $searchInput = $('#bouquetSearch');
                    const $bouquetOptions = $('.bouquet-option');
                    const $hiddenInput = $('#bouquet_id');

                    // Toggle dropdown
                    $dropdownButton.on('click', function (e) {
                        e.stopPropagation();
                        $dropdownPanel.toggleClass('hidden');
                        if (!$dropdownPanel.hasClass('hidden')) {
                            $searchInput.focus();
                        }
                    });

                    // Close dropdown when clicking outside
                    $(document).on('click', function (e) {
                        if (!$(e.target).closest($dropdownPanel).length && !$(e.target).closest($dropdownButton).length) {
                            $dropdownPanel.addClass('hidden');
                        }
                    });

                    // Prevent closing when clicking inside dropdown
                    $dropdownPanel.on('click', function (e) {
                        e.stopPropagation();
                    });

                    // Handle bouquet search
                    $searchInput.on('input', function () {
                        const searchTerm = $(this).val().toLowerCase();
                        $bouquetOptions.each(function () {
                            const $option = $(this);
                            const bouquetName = $option.data('name').toLowerCase();
                            if (bouquetName.includes(searchTerm)) {
                                $option.removeClass('hidden');
                            } else {
                                $option.addClass('hidden');
                            }
                        });
                    });

                    // Handle bouquet selection
                    $bouquetOptions.on('click', function (e) {
                        e.preventDefault();
                        const $selected = $(this);
                        const bouquetId = $selected.data('bouquet-id');
                        const bouquetName = $selected.data('original-name');

                        $dropdownButton.html(`<span class="text-gray-900">${bouquetName}</span>`);
                        $hiddenInput.val(bouquetId).trigger('change');
                        $dropdownPanel.addClass('hidden');

                        // Update global variables used by other functions
                        window.bouquetId = $selected.data('bouquet-id');
                        window.bouquetName = $selected.data('original-name');
                    });

                    // Custom Product Dropdown
                    const $productDropdownButton = $('#productDropdownButton');
                    const $productDropdownPanel = $('#productDropdownPanel');
                    const $productSearch = $('#productSearch');
                    const $productOptions = $('.product-option');
                    const $productHiddenInput = $('#product_id');

                    // Toggle product dropdown
                    $productDropdownButton.on('click', function (e) {
                        e.stopPropagation();
                        $productDropdownPanel.toggleClass('hidden');
                        if (!$productDropdownPanel.hasClass('hidden')) {
                            $productSearch.focus();
                        }
                    });

                    // Close product dropdown when clicking outside
                    $(document).on('click', function (e) {
                        if (!$(e.target).closest($productDropdownPanel).length && !$(e.target).closest($productDropdownButton).length) {
                            $productDropdownPanel.addClass('hidden');
                        }
                    });

                    // Prevent closing when clicking inside product dropdown
                    $productDropdownPanel.on('click', function (e) {
                        e.stopPropagation();
                    });

                    // Handle product search
                    $productSearch.on('input', function () {
                        const searchTerm = $(this).val().toLowerCase();
                        $productOptions.each(function () {
                            const $option = $(this);
                            const productName = $option.data('name').toLowerCase();
                            if (productName.includes(searchTerm)) {
                                $option.removeClass('hidden');
                            } else {
                                $option.addClass('hidden');
                            }
                        });
                    });

                    // Filter products by category
                    $('.category-tab').on('click', function () {
                        const categoryId = $(this).data('category');
                        $('.category-tab').removeClass('bg-pink-100');
                        $(this).addClass('bg-pink-100');

                        $productOptions.each(function () {
                            const $option = $(this);
                            if ($option.data('category') == categoryId) {
                                $option.removeClass('hidden');
                            } else {
                                $option.addClass('hidden');
                            }
                        });
                    });

                    // Handle product selection
                    $productOptions.on('click', function (e) {
                        e.preventDefault();
                        const $selected = $(this);
                        const productId = $selected.data('product-id');
                        const productName = $selected.data('original-name');

                        $productDropdownButton.html(`<span class="text-gray-900">${productName}</span>`);
                        $productHiddenInput.val(productId).trigger('change');
                        $productDropdownPanel.addClass('hidden');

                        // Update global variables used by other functions
                        window.productId = productId;
                        window.productName = productName;
                    });

                    // Custom Size Dropdown
                    const $sizeDropdownButton = $('#sizeDropdownButton');
                    const $sizeDropdownPanel = $('#sizeDropdownPanel');
                    const $sizeSearch = $('#sizeSearch');
                    const $sizeOptions = $('.size-option');
                    const $sizeHiddenInput = $('#size_id');

                    // Toggle size dropdown
                    $sizeDropdownButton.on('click', function (e) {
                        e.stopPropagation();
                        $sizeDropdownPanel.toggleClass('hidden');
                        if (!$sizeDropdownPanel.hasClass('hidden')) {
                            $sizeSearch.focus();
                        }
                    });

                    // Close size dropdown when clicking outside
                    $(document).on('click', function (e) {
                        if (!$(e.target).closest($sizeDropdownPanel).length && !$(e.target).closest($sizeDropdownButton).length) {
                            $sizeDropdownPanel.addClass('hidden');
                        }
                    });

                    // Prevent closing when clicking inside size dropdown
                    $sizeDropdownPanel.on('click', function (e) {
                        e.stopPropagation();
                    });

                    // Handle size search
                    $sizeSearch.on('input', function () {
                        const searchTerm = $(this).val().toLowerCase();
                        $sizeOptions.each(function () {
                            const $option = $(this);
                            const sizeName = $option.data('name').toLowerCase();
                            if (sizeName.includes(searchTerm)) {
                                $option.removeClass('hidden');
                            } else {
                                $option.addClass('hidden');
                            }
                        });
                    });

                    // Handle size selection
                    $sizeOptions.on('click', function (e) {
                        e.preventDefault();
                        const $selected = $(this);
                        const sizeId = $selected.data('size-id');
                        const sizeName = $selected.data('original-name');

                        $sizeDropdownButton.html(`<span class="text-gray-900">${sizeName}</span>`);
                        $sizeHiddenInput.val(sizeId).trigger('change');
                        $sizeDropdownPanel.addClass('hidden');

                        // Update global variables if needed
                        window.sizeId = sizeId;
                        window.sizeName = sizeName;
                    });
                });
            </script>
        @endpush

</x-app-layout>