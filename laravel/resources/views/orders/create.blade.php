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
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">No. Order (Yang Otomatis)</label>
                            <input type="text" class="form-input w-full rounded-md border-gray-300" value="(Akan otomatis)" disabled>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Pesanan</label>
                            <select name="status" id="status" class="form-input w-full rounded-md border-gray-300" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processed" {{ old('status') == 'processed' ? 'selected' : '' }}>Diproses</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tanggal Order</label>
                            <input type="text" class="form-input w-full rounded-md border-gray-300" value="{{ now()->format('d-m-Y H:i') }}" disabled>
                        </div>

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
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select id="orderCategorySelect" class="category-select mt-1 block w-full rounded-md border-gray-300" >
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Produk</label>
                                    <select id="orderProductSelect" class="product-select mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Pilih Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-category="{{ $product->category_id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Harga</label>
                                    <select id="orderPriceTypeSelect" class="price-type-select mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Pilih Tipe Harga</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" id="orderQtyInput" class="form-input w-full rounded-md border-gray-300" min="1" value="1">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded" id="addOrderItemBtn">Tambah Item</button>
                                </div>
                            </div>
                            <div class="overflow-x-auto rounded-lg shadow mb-2">
                                <table class="min-w-full divide-y divide-gray-200 bg-white" id="orderItemsTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipe Harga</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Daftar item pesanan akan diisi oleh JS -->
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="items" id="orderItemsInput">
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
        let orderItems = [];
        const orderProducts = @json($products);
        const orderProductPrices = {};
        orderProducts.forEach(p => {
            orderProductPrices[p.id] = p.prices;
        });
        function updateOrderItemsTable() {
            let tbody = document.querySelector('#orderItemsTable tbody');
            tbody.innerHTML = '';
            let subtotal = 0;
            if (orderItems.length === 0) {
                tbody.innerHTML = `<tr><td colspan='6' class='px-4 py-6 text-center text-gray-400'>Belum ada item ditambahkan.</td></tr>`;
            } else {
                orderItems.forEach((item, idx) => {
                    subtotal += item.price * item.quantity;
                    tbody.innerHTML += `
                        <tr>
                            <td class='px-4 py-2'>${item.product_name}</td>
                            <td class='px-4 py-2'>${item.price_type.replaceAll('_',' ').toUpperCase()}</td>
                            <td class='px-4 py-2'>${item.price.toLocaleString()}</td>
                            <td class='px-4 py-2'>${item.quantity}</td>
                            <td class='px-4 py-2'>${(item.price * item.quantity).toLocaleString()}</td>
                            <td class='px-4 py-2 text-center'><button type="button" class="text-red-600 hover:underline font-medium" onclick="removeOrderItem(${idx})">Hapus</button></td>
                        </tr>
                    `;
                });
            }
            document.getElementById('orderItemsInput').value = JSON.stringify(orderItems);
            updateOrderTotals();
        }
        function updateOrderTotals() {
            let subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const deliveryFeeRaw = document.getElementById('delivery_fee').value.replace(/[^0-9]/g, '');
            const deliveryFee = parseInt(deliveryFeeRaw) || 0;
            const dpRaw = document.getElementById('down_payment').value.replace(/[^0-9]/g, '');
            const dp = parseInt(dpRaw) || 0;
            const totalWithDelivery = subtotal + deliveryFee;
            const remaining = totalWithDelivery - dp;
            document.getElementById('totalAmount').textContent = subtotal.toLocaleString('id-ID', {style:'currency',currency:'IDR'});
            document.getElementById('totalWithDelivery').textContent = totalWithDelivery.toLocaleString('id-ID', {style:'currency',currency:'IDR'});
            document.getElementById('dpAmount').textContent = dp.toLocaleString('id-ID', {style:'currency',currency:'IDR'});
            document.getElementById('remainingAmount').textContent = remaining.toLocaleString('id-ID', {style:'currency',currency:'IDR'});
        }
        document.getElementById('delivery_fee').addEventListener('input', updateOrderTotals);
        document.getElementById('down_payment').addEventListener('input', updateOrderTotals);
        function removeOrderItem(idx) {
            orderItems.splice(idx, 1);
            updateOrderItemsTable();
        }
        function showStockAlert(stokTersedia, totalButuh) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Tidak Cukup',
                html: `Stok produk tidak mencukupi!<br><b>Stok tersedia:</b> ${stokTersedia}, <b>dibutuhkan:</b> ${totalButuh}`,
                confirmButtonText: 'Oke',
                customClass: {
                    confirmButton: 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600',
                }
            });
        }
        document.getElementById('addOrderItemBtn').onclick = function() {
            let productId = document.getElementById('orderProductSelect').value;
            let priceType = document.getElementById('orderPriceTypeSelect').value;
            let priceTypeOption = document.querySelector(`#orderPriceTypeSelect option[value='${priceType}']`);
            let quantity = parseInt(document.getElementById('orderQtyInput').value);
            if (!productId || !priceType || !quantity || quantity < 1) return Swal.fire('Lengkapi data item!');
            let product = orderProducts.find(p => p.id == productId);
            let price = priceTypeOption ? parseFloat(priceTypeOption.getAttribute('data-price')) : 0;
            // Cek stok produk sebelum tambah
            let priceObj = (product.prices || []).find(pr => pr.type === priceType);
            let unitEquivalent = priceObj && priceObj.unit_equivalent ? parseInt(priceObj.unit_equivalent) : 1;
            let stokTersedia = product.current_stock;
            let totalButuh = quantity * unitEquivalent;
            if (stokTersedia < totalButuh) {
                showStockAlert(stokTersedia, totalButuh);
                return;
            }
            orderItems.push({
                product_id: product.id,
                product_name: product.name,
                price_type: priceType,
                quantity: quantity,
                price: price,
                qty: quantity // pastikan qty selalu ada
            });
            updateOrderItemsTable();
        };
        document.getElementById('orderCategorySelect').onchange = function() {
            let catId = this.value;
            let productSelect = document.getElementById('orderProductSelect');
            Array.from(productSelect.options).forEach(opt => {
                if (!opt.value) return;
                opt.style.display = opt.getAttribute('data-category') == catId ? '' : 'none';
            });
            productSelect.value = '';
        };
        document.getElementById('orderProductSelect').onchange = function() {
            let productId = this.value;
            let priceTypeSelect = document.getElementById('orderPriceTypeSelect');
            priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
            if (orderProductPrices[productId]) {
                orderProductPrices[productId].forEach(price => {
                    priceTypeSelect.innerHTML += `<option value="${price.type}" data-price="${price.price}">${price.type.replaceAll('_',' ').toUpperCase()} (Rp ${parseFloat(price.price).toLocaleString()})</option>`;
                });
            }
        };
        document.getElementById('orderForm').onsubmit = function() {
            if (orderItems.length === 0) {
                alert('Tambahkan minimal 1 item pesanan sebelum membuat pesanan!');
                return false;
            }
            // Pastikan qty selalu ada di setiap item
            orderItems = orderItems.map(item => ({...item, qty: item.quantity || item.qty || 1}));
            document.getElementById('orderItemsInput').value = JSON.stringify(orderItems);
        };
        // Inisialisasi tabel pertama kali
        updateOrderItemsTable();

        function toggleDeliveryAddress(select) {
            const container = document.getElementById('deliveryAddressContainer');
            const value = select.value.toLowerCase();
            if (value === 'pickup' || value === 'ambil langsung') {
                container.classList.add('hidden');
                document.getElementById('delivery_address').value = '';
                document.getElementById('delivery_fee').value = 0;
            } else {
                container.classList.remove('hidden');
            }
            updateOrderTotals && updateOrderTotals();
        }
        // Pastikan field alamat pengiriman muncul/tersembunyi sesuai pilihan saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', function() {
            toggleDeliveryAddress(document.getElementById('delivery_method'));
            document.getElementById('delivery_method').addEventListener('change', function() {
                toggleDeliveryAddress(this);
            });
        });
    </script>
</x-app-layout>
