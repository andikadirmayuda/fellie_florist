<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Inventaris') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form action="{{ route('inventory.index') }}" method="GET" class="flex gap-4 items-end">
                            <div>
                                <x-input-label for="search" :value="__('Cari')" />
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                    value="{{ request('search') }}" placeholder="Cari produk..." />
                            </div>                            <div>
                                <x-input-label for="category" :value="__('Kategori')" />
                                <select id="category" name="category"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Kategori</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="low_stock" id="low_stock" value="1" 
                                    {{ request('low_stock') ? 'checked' : '' }}
                                    class="rounded border-gray-300 shadow-sm">
                                <x-input-label for="low_stock" :value="__('Stok Menipis')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Terapkan Filter') }}
                            </x-primary-button>
                        </form>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto">                    <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stok Saat Ini
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stok Minimal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->auto_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $product->category?->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $product->formatted_stock }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($product->min_stock) }} {{ $product->base_unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">                                        @if($product->needs_restock)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Stok Menipis
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Tersedia
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('inventory.history', $product) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">Riwayat</a>
                                        <a href="{{ route('inventory.adjust-form', $product) }}" 
                                           class="text-green-600 hover:text-green-900">Sesuaikan</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
