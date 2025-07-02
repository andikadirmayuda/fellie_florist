

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Penyesuaian Stok') }}
                @if(isset($product) && $product)
                    - {{ $product->name }}
                @endif
            </h2>
            <a href="{{ route('inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Kembali ke Inventaris') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($product) && $product)
                        <!-- Current Stock Info -->
                        <div class="mb-6 bg-gray-50 p-4 rounded">
                            <h3 class="text-sm font-medium text-gray-500">Stok Saat Ini</h3>
                            <p class="mt-1 text-lg font-semibold">{{ $product->formatted_stock }}</p>
                        </div>
                    @endif

                    <!-- Adjustment Form -->
                    <form method="POST" id="adjustForm"
                        action="{{ isset($product) && $product ? route('inventory.adjust', $product) : '' }}"
                        class="max-w-xl">
                        @csrf

                        @if(!isset($product) || !$product)
                            <div class="mb-4">
                                <x-input-label for="product_id" :value="__('Pilih Produk')" />
                                <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" data-action="{{ route('inventory.adjust', $prod) }}">
                                            {{ $prod->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                                <div id="current-stock-info" class="mt-2 bg-gray-50 p-3 rounded text-gray-800 text-sm hidden"></div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <x-input-label for="quantity" :value="__('Jumlah Stok Baru')" />
                            <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full"
                                :value="old('quantity', isset($product) && $product ? $product->current_stock : '')" required min="0" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">
                                Masukkan jumlah total stok yang baru. Sistem akan menghitung penyesuaiannya secara otomatis.
                            </p>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Catatan Penyesuaian')" />
                            <textarea id="notes" name="notes"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                rows="3"
                                placeholder="Masukkan alasan penyesuaian">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Sesuaikan Stok') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if(!isset($product) || !$product)
        <script>
            // Ubah action form sesuai produk yang dipilih, tampilkan info stok, dan prevent submit jika belum pilih produk
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('product_id');
                const form = document.getElementById('adjustForm');
                const stockInfo = document.getElementById('current-stock-info');
                select.addEventListener('change', function() {
                    const selected = select.options[select.selectedIndex];
                    const action = selected.getAttribute('data-action');
                    form.action = action ? action : '';
                    // Reset info
                    stockInfo.classList.add('hidden');
                    stockInfo.innerHTML = '';
                    if (select.value) {
                        fetch(`/api/products/${select.value}/stock`)
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.current_stock !== undefined && data.base_unit) {
                                    stockInfo.innerHTML = `<b>Stok Saat Ini:</b> ${data.current_stock} ${data.base_unit}`;
                                    stockInfo.classList.remove('hidden');
                                }
                            });
                    }
                });
                form.addEventListener('submit', function(e) {
                    if (!select.value) {
                        e.preventDefault();
                        alert('Silakan pilih produk terlebih dahulu!');
                    } else if (!form.action) {
                        e.preventDefault();
                        alert('Terjadi kesalahan pada form. Silakan pilih ulang produk.');
                    }
                });
            });
        </script>
    @endif

</x-app-layout>
