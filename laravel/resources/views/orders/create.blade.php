<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <div class="mb-4">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan</label>
                            <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach(\App\Models\Customer::all() as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>                        <!-- Informasi Pengiriman -->
                        <div class="mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="pickup_date" class="block text-sm font-medium text-gray-700">Tanggal Pengambilan</label>
                                    <input type="datetime-local" name="pickup_date" id="pickup_date" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    @error('pickup_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="delivery_method" class="block text-sm font-medium text-gray-700">Metode Pengiriman</label>
                                    <select name="delivery_method" id="delivery_method" 
                                        onchange="toggleDeliveryAddress(this)"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="pickup">Ambil Langsung</option>
                                        <option value="gosend">GoSend</option>
                                        <option value="gocar">GoCar</option>
                                    </select>
                                    @error('delivery_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="deliveryAddressContainer" class="md:col-span-2 hidden">
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                                    <textarea name="delivery_address" id="delivery_address" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                    @error('delivery_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Item Pesanan</h3>
                            <div class="flex justify-end mb-2">
                                <button type="button" onclick="addOrderItem()" class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded">
                                    Tambah Item Baru
                                </button>
                            </div>
                            <div id="orderItems" class="space-y-4">
                                <!-- Order items will be added here dynamically -->
                            </div>
                            @error('items')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Biaya & Pembayaran -->
                        <div class="mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Biaya & Pembayaran</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="delivery_fee" class="block text-sm font-medium text-gray-700">Biaya Pengiriman</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="text" name="delivery_fee" id="delivery_fee" value="0"
                                            class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            onchange="updateTotalAndRemaining()" placeholder="0">
                                    </div>
                                    @error('delivery_fee')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="down_payment" class="block text-sm font-medium text-gray-700">Uang Muka (DP)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="text" name="down_payment" id="down_payment" value="0"
                                            class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            onchange="updateRemaining()" placeholder="0">
                                    </div>
                                    @error('down_payment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dl class="grid grid-cols-2 gap-4">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Total Pesanan:</dt>
                                                <dd class="mt-1 text-lg font-semibold text-gray-900" id="totalAmount">Rp 0</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Total + Ongkir:</dt>
                                                <dd class="mt-1 text-lg font-semibold text-gray-900" id="totalWithDelivery">Rp 0</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Uang Muka:</dt>
                                                <dd class="mt-1 text-lg font-semibold text-green-600" id="dpAmount">Rp 0</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Sisa Pembayaran:</dt>
                                                <dd class="mt-1 text-lg font-semibold text-blue-600" id="remainingAmount">Rp 0</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Create Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php $categories = $categories ?? []; @endphp
    <template id="orderItemTemplate">
        <div class="order-item border rounded p-4 relative">
            <button type="button" onclick="this.closest('.order-item').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select data-field="category_id" class="category-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Produk</label>
                    <select name="items[0][product_id]" data-field="product_id" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                        <option value="">Pilih Produk</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Harga</label>
                    <select name="items[0][price_type]" data-field="price_type" onchange="updatePrice(this)" class="price-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                        <option value="">Pilih Tipe Harga</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500 selected-price"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="items[0][qty]" data-field="qty" min="1" value="1" onchange="updateSubtotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <p class="mt-1 text-sm text-gray-500 subtotal"></p>
                </div>
            </div>
        </div>
    </template>
    <script>
const categoriesData = @json($categories);
        let itemIndex = 0;
        const priceFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        function toggleDeliveryAddress(select) {
            const container = document.getElementById('deliveryAddressContainer');
            if (select.value === 'pickup') {
                container.classList.add('hidden');
                document.getElementById('delivery_address').value = '';
                document.getElementById('delivery_fee').value = 0;
            } else {
                container.classList.remove('hidden');
            }
            updateTotalAndRemaining();
        }

        function updateTotalAndRemaining() {
            const subtotals = Array.from(document.querySelectorAll('.subtotal')).map(el => {
                const text = el.textContent;
                return text ? parseInt(text.replace(/[^0-9]/g, '')) : 0;
            });

            const total = subtotals.reduce((a, b) => a + b, 0);
            // Ambil value delivery_fee dan down_payment tanpa titik
            const deliveryFeeRaw = document.getElementById('delivery_fee').value.replace(/[^0-9]/g, '');
            const deliveryFee = parseInt(deliveryFeeRaw) || 0;
            const dpRaw = document.getElementById('down_payment').value.replace(/[^0-9]/g, '');
            const dp = parseInt(dpRaw) || 0;
            const totalWithDelivery = total + deliveryFee;
            const remaining = totalWithDelivery - dp;

            document.getElementById('totalAmount').textContent = priceFormatter.format(total);
            document.getElementById('totalWithDelivery').textContent = priceFormatter.format(totalWithDelivery);
            document.getElementById('dpAmount').textContent = priceFormatter.format(dp);
            document.getElementById('remainingAmount').textContent = priceFormatter.format(remaining);
        }

        function updateRemaining() {
            const totalWithDelivery = parseFloat(document.getElementById('totalWithDelivery').textContent.replace(/[^0-9]/g, ''));
            // Ambil value DP dan hapus semua karakter non-angka
            const dpRaw = document.getElementById('down_payment').value.replace(/[^0-9]/g, '');
            const dp = parseFloat(dpRaw) || 0;
            const remaining = totalWithDelivery - dp;

            document.getElementById('dpAmount').textContent = priceFormatter.format(dp);
            document.getElementById('remainingAmount').textContent = priceFormatter.format(remaining);
        }

        function updateProductOptions(categorySelect) {
            const orderItem = categorySelect.closest('.order-item');
            const productSelect = orderItem.querySelector('.product-select');
            const priceTypeSelect = orderItem.querySelector('.price-type-select');
            const selectedCategoryId = categorySelect.value;
            productSelect.innerHTML = '<option value="">Pilih Produk</option>';
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            priceTypeSelect.disabled = true;
            if (!selectedCategoryId) {
                productSelect.disabled = true;
                return;
            }
            const category = categoriesData.find(cat => cat.id == selectedCategoryId);
            if (category && category.products && category.products.length > 0) {
                category.products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    option.dataset.prices = JSON.stringify(product.prices);
                    productSelect.appendChild(option);
                });
                productSelect.disabled = false;
            } else {
                productSelect.disabled = false; // Perbaiki: tetap enable agar user bisa klik meski kosong
            }
        }

        function addOrderItem() {
            const template = document.getElementById('orderItemTemplate');
            const orderItems = document.getElementById('orderItems');
            const clone = template.content.cloneNode(true);
            itemIndex++;
            const inputs = clone.querySelectorAll('input, select');
            inputs.forEach(input => {
                const field = input.dataset.field;
                if (field) input.name = `items[${itemIndex}][${field}]`;
            });
            // Tambah event listener kategori
            const categorySelect = clone.querySelector('.category-select');
            categorySelect.addEventListener('change', function() {
                updateProductOptions(this);
            });
            // Tambah event listener produk
            const productSelect = clone.querySelector('.product-select');
            productSelect.addEventListener('change', function() {
                loadPriceTypes(this);
            });
            orderItems.appendChild(clone);
        }

        function loadPriceTypes(productSelect) {
            const orderItem = productSelect.closest('.order-item');
            const priceTypeSelect = orderItem.querySelector('.price-type-select');
            const selectedPrice = orderItem.querySelector('.selected-price');
            const option = productSelect.options[productSelect.selectedIndex];
            
            // Reset price type select
            priceTypeSelect.innerHTML = '<option value="">Select Price Type</option>';
            selectedPrice.textContent = '';
            
            if (option.value) {
                const prices = JSON.parse(option.dataset.prices);
                priceTypeSelect.disabled = false;

                prices.forEach(price => {
                    const typeLabel = price.type
                        .split('_')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                    
                    const option = new Option(
                        `${typeLabel} - ${priceFormatter.format(price.price)}`, 
                        price.type
                    );
                    option.dataset.price = price.price;
                    priceTypeSelect.add(option);
                });
            } else {
                priceTypeSelect.disabled = true;
            }

            updateSubtotal(productSelect);
        }

        function updatePrice(priceTypeSelect) {
            const orderItem = priceTypeSelect.closest('.order-item');
            const selectedPrice = orderItem.querySelector('.selected-price');
            const option = priceTypeSelect.options[priceTypeSelect.selectedIndex];

            if (option.value) {
                const price = option.dataset.price;
                selectedPrice.textContent = `Price: ${priceFormatter.format(price)}`;
            } else {
                selectedPrice.textContent = '';
            }

            updateSubtotal(priceTypeSelect);
        }

        function updateSubtotal(element) {
            const orderItem = element.closest('.order-item');
            const priceTypeSelect = orderItem.querySelector('.price-type-select');
            const qtyInput = orderItem.querySelector('input[data-field="qty"]');
            const subtotalElement = orderItem.querySelector('.subtotal');

            const selectedOption = priceTypeSelect.options[priceTypeSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.price) {
                const price = parseFloat(selectedOption.dataset.price);
                const qty = parseInt(qtyInput.value) || 0;
                const subtotal = price * qty;
                subtotalElement.textContent = `${subtotal}`; // Simpan angka murni
            } else {
                subtotalElement.textContent = '';
            }
            // Setelah update subtotal, update total dan sisa pembayaran
            updateTotalAndRemaining();
        }

        // Format angka ribuan pada input delivery_fee dan down_payment
        function formatNumberInput(input) {
            let value = input.value.replace(/\D/g, '');
            if (!value) value = '0';
            input.value = parseInt(value, 10).toLocaleString('id-ID');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const deliveryInput = document.getElementById('delivery_fee');
            const downPaymentInput = document.getElementById('down_payment');
            if (deliveryInput) {
                deliveryInput.addEventListener('input', function() {
                    formatNumberInput(this);
                });
            }
            if (downPaymentInput) {
                downPaymentInput.addEventListener('input', function() {
                    formatNumberInput(this);
                });
            }
        });

        // Add first item by default
        document.addEventListener('DOMContentLoaded', function() {
            addOrderItem();
        });
    </script>
</x-app-layout>
