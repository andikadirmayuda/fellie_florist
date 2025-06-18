<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order') }} - {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Order -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Order Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Order Number</label>
                                    <p class="mt-1 text-sm text-gray-600">{{ $order->order_number }}</p>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @foreach(['pending', 'processed', 'completed', 'cancelled'] as $status)
                                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Item Pesanan -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                                <button type="button" onclick="addOrderItem()" class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded">
                                    Add New Item
                                </button>
                            </div>

                            <div id="orderItems" class="space-y-4">
                                @foreach($order->items as $index => $item)
                                    <div class="order-item border rounded p-4 relative">
                                        <button type="button" onclick="this.closest('.order-item').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700">Product</label>
                                                <select name="items[{{ $index }}][product_id]" data-field="product_id" onchange="loadPriceTypes(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" 
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}
                                                            data-prices='@json($product->prices)'>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Price Type</label>
                                                <select name="items[{{ $index }}][price_type]" data-field="price_type" onchange="updatePrice(this)" class="price-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                    <option value="">Select Price Type</option>
                                                </select>
                                                <p class="mt-1 text-sm text-gray-500 selected-price"></p>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                                <input type="number" name="items[{{ $index }}][qty]" data-field="qty" min="1" value="{{ $item->qty }}" onchange="updateSubtotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                <p class="mt-1 text-sm text-gray-500 subtotal"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('orders.show', $order) }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <template id="orderItemTemplate">
        <div class="order-item border rounded p-4 relative">
            <button type="button" onclick="this.closest('.order-item').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="items[0][product_id]" data-field="product_id" onchange="loadPriceTypes(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-prices='@json($product->prices)'>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price Type</label>
                    <select name="items[0][price_type]" data-field="price_type" onchange="updatePrice(this)" class="price-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Select Price Type</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500 selected-price"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="items[0][qty]" data-field="qty" min="1" value="1" onchange="updateSubtotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <p class="mt-1 text-sm text-gray-500 subtotal"></p>
                </div>
            </div>
        </div>
    </template>

    <script>
        let itemIndex = {{ count($order->items) }};
        const priceFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        });

        function addOrderItem() {
            const template = document.getElementById('orderItemTemplate');
            const orderItems = document.getElementById('orderItems');
            const clone = template.content.cloneNode(true);
            
            // Update the index for the new item
            itemIndex++;
            const inputs = clone.querySelectorAll('input, select');
            inputs.forEach(input => {
                const field = input.dataset.field;
                input.name = `items[${itemIndex}][${field}]`;
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
                subtotalElement.textContent = `Subtotal: ${priceFormatter.format(subtotal)}`;
            } else {
                subtotalElement.textContent = '';
            }
        }

        // Initialize price types for existing items
        document.querySelectorAll('.order-item select[data-field="product_id"]').forEach(select => {
            loadPriceTypes(select);
            const orderItem = select.closest('.order-item');
            const priceTypeSelect = orderItem.querySelector('.price-type-select');
            const savedPriceType = '{{ $item->price_type }}';
            
            if (savedPriceType) {
                Array.from(priceTypeSelect.options).forEach(option => {
                    if (option.value === savedPriceType) {
                        option.selected = true;
                        updatePrice(priceTypeSelect);
                    }
                });
            }
        });
    </script>
</x-app-layout>
