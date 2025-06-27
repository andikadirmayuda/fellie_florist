<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" 
                          method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product)) @method('PUT') @endif

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Produk (opsional)</label>
                                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @if(isset($product) && $product->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="h-24 rounded">
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $id => $name)
                                        <option value="{{ $id }}" {{ (old('category_id', $product->category_id ?? '') == $id) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Kode Produk <span class="text-xs text-gray-400">(opsional, isi manual jika perlu)</span></label>
                                <input type="text" name="code" id="code"
                                       value="{{ old('code', $product->code ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $product->name ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Satuan Dasar</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="base_unit" value="tangkai" 
                                               {{ old('base_unit', $product->base_unit ?? 'tangkai') == 'tangkai' ? 'checked' : '' }}
                                               class="form-radio">
                                        <span class="ml-2">Tangkai</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="base_unit" value="item"
                                               {{ old('base_unit', $product->base_unit ?? '') == 'item' ? 'checked' : '' }}
                                               class="form-radio">
                                        <span class="ml-2">Item</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="current_stock" class="block text-sm font-medium text-gray-700">Stok Saat Ini</label>
                                <input type="number" name="current_stock" id="current_stock"
                                       value="{{ old('current_stock', $product->current_stock ?? 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700">Minimal Stok</label>
                                <input type="number" name="min_stock" id="min_stock"
                                       value="{{ old('min_stock', $product->min_stock ?? 10) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                                       class="form-checkbox">
                                <span class="ml-2">Aktif</span>
                            </label>
                        </div>

                        <!-- Price Section -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900">Harga Produk</h3>
                            <div class="mt-4">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jenis Harga
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Harga (Rp)
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unit Equivalent
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Default
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($priceTypes as $type)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                                    <input type="hidden" name="prices[{{ $type }}][type]" value="{{ $type }}">
                                                </td>
                                                <td class="px-6 py-4">                                                <div class="relative">
                                                    <input type="number" step="0.01"
                                                           name="prices[{{ $type }}][price]"
                                                           value="{{ old("prices.$type.price", $existingPrices[$type]->price ?? '') }}"
                                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $errors->has("prices.$type.price") ? 'border-red-300' : '' }}"
                                                           placeholder="Opsional">
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                                        <small>Rp</small>
                                                    </div>
                                                    @error("prices.$type.price")
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                </td>
                                                <td class="px-6 py-4">                                                    <input type="number"
                                                           name="prices[{{ $type }}][unit_equivalent]"
                                                           value="{{ old("prices.$type.unit_equivalent", 
                                                                        $existingPrices[$type]->unit_equivalent ?? 
                                                                        $defaultUnitEquivalents[$type]) }}"
                                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $errors->has("prices.$type.unit_equivalent") ? 'border-red-300' : '' }}">
                                                    @error("prices.$type.unit_equivalent")
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td class="px-6 py-4">                                                    <input type="radio" 
                                                           name="default_price_type" 
                                                           value="{{ $type }}"
                                                           {{ (old('default_price_type', $existingPrices[$type]->is_default ?? false) ? $type : '') === $type ? 'checked' : '' }}
                                                           class="form-radio text-blue-600">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('products.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ isset($product) ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
