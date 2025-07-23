<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-lg mr-3">
                    <i class="bi bi-cart-plus text-pink-600 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Transaksi Penjualan Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Buat transaksi penjualan baru</p>
                </div>
            </div>
            <a href="{{ route('sales.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <i class="bi bi-arrow-left mr-2"></i>
                <span class="hidden sm:inline">Kembali</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill mr-2 text-red-600"></i>
                        <span class="font-medium">Terdapat kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Transaksi</h3>
                    <p class="mt-1 text-sm text-gray-500">Lengkapi data transaksi penjualan</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="space-y-8">
                        @csrf

                        <!-- Customer & Transaction Info -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-whatsapp mr-2 text-green-600"></i>No. WhatsApp Customer
                                    </label>
                                    <input type="text" name="wa_number"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        placeholder="08xxxxxxxxxx" autocomplete="off">
                                    <p class="mt-1 text-xs text-gray-500">Untuk mengirim link invoice ke customer</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-clock mr-2 text-blue-600"></i>Waktu Pemesanan
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                        value="{{ now()->format('d/m/Y H:i') }}" disabled>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-hash mr-2 text-purple-600"></i>No. Penjualan
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                        value="(Akan otomatis dibuat)" disabled>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-credit-card mr-2 text-orange-600"></i>Metode Pembayaran
                                    </label>
                                    <select name="payment_method"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash">Cash/Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="debit">Debit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Product Selection -->
                        <div class="border-t border-gray-200 pt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-6">Pilih Produk</h4>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-tags mr-2 text-indigo-600"></i>Kategori Produk
                                    </label>
                                    <select
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        id="categorySelect">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-box mr-2 text-teal-600"></i>Produk
                                    </label>
                                    <select
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        id="productSelect">
                                        <option value="">Pilih Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                                @if($product->current_stock == 0) disabled @endif>
                                                {{ $product->name }}
                                                @if($product->current_stock == 0)
                                                    (Stok Habis)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-cash-coin mr-2 text-yellow-600"></i>Tipe Harga
                                    </label>
                                    <select
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        id="priceTypeSelect">
                                        <option value="">Pilih Tipe Harga</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-123 mr-2 text-red-600"></i>Jumlah
                                    </label>
                                    <input type="number"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        id="quantityInput" min="1" value="1" placeholder="Masukkan jumlah">
                                </div>

                                <div class="flex items-end">
                                    <button type="button" id="addItemBtn"
                                        class="w-full px-4 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="bi bi-plus-circle mr-2"></i>Tambah Item
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Search -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-upc-scan mr-2 text-gray-600"></i>Cari Cepat dengan Kode Produk
                                </label>
                                <div class="relative">
                                    <input type="text" id="searchByCodeInput"
                                        class="w-full px-3 py-2.5 pl-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors"
                                        placeholder="Scan atau ketik kode produk...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Table -->
                        <div class="border-t border-gray-200 pt-8">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-lg font-medium text-gray-900">
                                    <i class="bi bi-cart-check mr-2 text-green-600"></i>Daftar Produk
                                </h4>
                                <div id="searchByCodeResult"></div>
                            </div>

                            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Tipe Harga
                                            </th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Harga
                                            </th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Jumlah
                                            </th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <!-- Items will be added by JavaScript -->
                                        <tr id="emptyRow">
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="bi bi-cart text-gray-300 text-4xl mb-4"></i>
                                                    <p class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong
                                                    </p>
                                                    <p class="text-sm">Tambahkan produk untuk memulai transaksi</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="border-t border-gray-200 pt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-6">
                                <i class="bi bi-calculator mr-2 text-purple-600"></i>Ringkasan Pesanan
                            </h4>

                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Subtotal
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-600"
                                                id="subtotalInput" name="subtotal" readonly>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <strong>Total</strong>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-700 font-semibold">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 font-semibold text-lg"
                                                id="totalInput" name="total" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Payment Section -->
                        <div id="cashSection" class="border-t border-gray-200 pt-8" style="display:none;">
                            <h4 class="text-lg font-medium text-gray-900 mb-6">
                                <i class="bi bi-cash mr-2 text-green-600"></i>Pembayaran Cash
                            </h4>

                            <div class="bg-green-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Uang yang Diterima
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                                id="cashGivenInput" placeholder="0" autocomplete="off">
                                        </div>

                                        <!-- Quick Amount Buttons -->
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <button type="button" onclick="setExactAmount()"
                                                class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                                                Pas
                                            </button>
                                            <button type="button" onclick="addAmount(5000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +5k
                                            </button>
                                            <button type="button" onclick="addAmount(10000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +10k
                                            </button>
                                            <button type="button" onclick="addAmount(20000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +20k
                                            </button>
                                            <button type="button" onclick="addAmount(50000)"
                                                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                                +50k
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Kembalian
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">Rp</span>
                                            </div>
                                            <div
                                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-semibold flex items-center">
                                                <span id="changeAmount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="border-t border-gray-200 pt-8">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                <div class="text-sm text-gray-500">
                                    Pastikan semua data sudah benar sebelum menyimpan transaksi
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('sales.index') }}"
                                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                        <i class="bi bi-x-circle mr-2"></i>Batal
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="submitBtn">
                                        <i class="bi bi-save mr-2"></i>Simpan Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="items" id="itemsInput">
                        <input type="hidden" name="cash_given" id="hiddenCashGivenInput">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let items = [];
        const products = @json($products);

        // Buat mapping harga per produk
        const productPrices = {};
        products.forEach(p => {
            productPrices[p.id] = p.prices;
        });

        // Elements
        const productSelect = document.getElementById('productSelect');
        const priceTypeSelect = document.getElementById('priceTypeSelect');
        const quantityInput = document.getElementById('quantityInput');
        const addItemBtn = document.getElementById('addItemBtn');
        const subtotalInput = document.getElementById('subtotalInput');
        const totalInput = document.getElementById('totalInput');
        const itemsInput = document.getElementById('itemsInput');
        const cashGivenInput = document.getElementById('cashGivenInput');
        const changeAmount = document.getElementById('changeAmount');
        const cashSection = document.getElementById('cashSection');
        const emptyRow = document.getElementById('emptyRow');

        // Product selection handler
        productSelect.onchange = function () {
            let productId = this.value;
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';

            if (productPrices[productId]) {
                productPrices[productId].forEach(price => {
                    priceTypeSelect.innerHTML += `<option value="${price.type}" data-price="${price.price}">${price.type.replaceAll('_', ' ').toUpperCase()} (Rp ${parseFloat(price.price).toLocaleString('id-ID')})</option>`;
                });
            }
        };

        // Payment method handler
        document.querySelector('select[name="payment_method"]').onchange = function () {
            if (this.value === 'cash') {
                cashSection.style.display = 'block';
            } else {
                cashSection.style.display = 'none';
            }
        };

        // Cash change calculator
        function updateCashChange() {
            const total = parseInt((totalInput.value || '').replace(/[^0-9]/g, '')) || 0;
            const given = parseInt((cashGivenInput.value || '').replace(/[^0-9]/g, '')) || 0;
            let change = given - total;

            // Update kembalian display
            changeAmount.textContent = formatRupiah(change >= 0 ? change : 0);
            document.getElementById('hiddenCashGivenInput').value = given;

            // Update styling based on payment adequacy
            const changeContainer = changeAmount.parentElement;
            if (given < total && given > 0) {
                changeContainer.classList.remove('text-green-700', 'bg-gray-100');
                changeContainer.classList.add('text-red-700', 'bg-red-50');
                changeAmount.textContent = `KURANG ${formatRupiah(total - given)}`;
            } else if (given >= total && given > 0) {
                changeContainer.classList.remove('text-red-700', 'bg-red-50');
                changeContainer.classList.add('text-green-700', 'bg-green-50');
                changeAmount.textContent = formatRupiah(change);
            } else {
                changeContainer.classList.remove('text-red-700', 'bg-red-50', 'text-green-700', 'bg-green-50');
                changeContainer.classList.add('bg-gray-100');
                changeAmount.textContent = '0';
            }
        }

        // Format currency
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        // Update table display
        function updateTable() {
            let tbody = document.querySelector('#itemsTable tbody');
            tbody.innerHTML = '';
            let subtotal = 0;

            if (items.length === 0) {
                tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="bi bi-cart text-gray-300 text-4xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong</p>
                            <p class="text-sm">Tambahkan produk untuk memulai transaksi</p>
                        </div>
                    </td>
                </tr>
            `;
            } else {
                items.forEach((item, idx) => {
                    subtotal += item.price * item.quantity;
                    tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-900">${item.product_name}</td>
                        <td class="px-6 py-4 text-gray-600">${item.price_type.replaceAll('_', ' ').toUpperCase()}</td>
                        <td class="px-6 py-4 text-right text-gray-900">Rp ${item.price.toLocaleString('id-ID')}</td>
                        <td class="px-6 py-4 text-center text-gray-900">${item.quantity}</td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" 
                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-md transition-colors duration-150"
                                onclick="removeItem(${idx})">
                                <i class="bi bi-trash mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                `;
                });
            }

            subtotalInput.value = formatRupiah(subtotal);
            totalInput.value = formatRupiah(subtotal);
            itemsInput.value = JSON.stringify(items);
            updateCashChange();
        }

        // Remove item from cart
        function removeItem(idx) {
            items.splice(idx, 1);
            updateTable();
        }

        // Add item to cart
        addItemBtn.onclick = function () {
            let productId = productSelect.value;
            let priceType = priceTypeSelect.value;
            let priceTypeOption = priceTypeSelect.querySelector(`option[value='${priceType}']`);
            let quantity = parseInt(quantityInput.value);

            if (!productId || !priceType || quantity < 1) {
                alert('Lengkapi data produk!');
                return;
            }

            let product = products.find(p => p.id == productId);
            let price = priceTypeOption ? parseFloat(priceTypeOption.getAttribute('data-price')) : 0;

            // Cek stok produk sebelum menambah
            let priceObj = (product.prices || []).find(pr => pr.type === priceType);
            let unitEquivalent = priceObj && priceObj.unit_equivalent ? parseInt(priceObj.unit_equivalent) : 1;
            let stokTersedia = product.current_stock;
            let totalButuh = quantity * unitEquivalent;

            if (stokTersedia < totalButuh) {
                alert(`Stok produk tidak mencukupi!\nStok tersedia: ${stokTersedia}\nDibutuhkan: ${totalButuh}`);
                return;
            }

            items.push({
                product_id: product.id,
                product_name: product.name,
                price_type: priceType,
                quantity: quantity,
                price: price
            });

            updateTable();

            // Reset form
            productSelect.value = '';
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            quantityInput.value = '1';
        };

        // Category filter
        document.getElementById('categorySelect').onchange = function () {
            let catId = this.value;
            Array.from(productSelect.options).forEach(opt => {
                if (!opt.value) return;
                opt.style.display = opt.getAttribute('data-category') == catId ? '' : 'none';
            });
            productSelect.value = '';
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
        };

        // Cash input formatting with real-time formatting
        if (cashGivenInput) {
            cashGivenInput.oninput = function () {
                // Remove non-numeric characters
                let value = this.value.replace(/[^0-9]/g, '');

                // Format with thousand separators
                if (value) {
                    this.value = formatRupiah(parseInt(value));
                } else {
                    this.value = '';
                }

                updateCashChange();
            };
        }

        // Quick amount functions
        function setExactAmount() {
            const total = parseInt((totalInput.value || '').replace(/[^0-9]/g, '')) || 0;
            if (total > 0) {
                cashGivenInput.value = formatRupiah(total);
                updateCashChange();
            }
        }

        function addAmount(amount) {
            const current = parseInt((cashGivenInput.value || '').replace(/[^0-9]/g, '')) || 0;
            const newAmount = current + amount;
            cashGivenInput.value = formatRupiah(newAmount);
            updateCashChange();
        }

        // Form submission validation
        document.getElementById('saleForm').onsubmit = function (e) {
            if (items.length === 0) {
                alert('Tambahkan minimal 1 produk!');
                return false;
            }

            const paymentSelect = document.querySelector('select[name="payment_method"]');
            if (paymentSelect.value === 'cash') {
                const total = parseInt((totalInput.value || '').replace(/[^0-9]/g, '')) || 0;
                const given = parseInt((cashGivenInput.value || '').replace(/[^0-9]/g, '')) || 0;

                if (given < total) {
                    e.preventDefault();
                    alert(`Uang yang diberikan kurang dari total belanja!\n\nTotal: Rp ${formatRupiah(total)}\nDiberikan: Rp ${formatRupiah(given)}\nKurang: Rp ${formatRupiah(total - given)}`);
                    cashGivenInput.focus();
                    cashGivenInput.select();
                    return false;
                }
            }

            return true;
        };

        // Search by code functionality
        document.getElementById('searchByCodeInput').addEventListener('input', function () {
            let code = this.value.trim();
            if (code.length >= 3) {
                let product = products.find(p => p.code && p.code.toLowerCase().includes(code.toLowerCase()));
                if (product) {
                    productSelect.value = product.id;
                    productSelect.dispatchEvent(new Event('change'));
                }
            }
        });

        // Initialize table
        updateTable();
    </script>
</x-app-layout>