<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Daftar Produk</h1>
            <a href="{{ route('products.create') }}"
                class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Produk</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Tombol Ekspor & Form Impor JSON -->
            {{-- <div class="flex justify-end mb-4 space-x-2">
                <form action="{{ route('products.import-json') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center space-x-2">
                    @csrf
                    <input type="file" name="json_file" accept=".json" required
                        class="border rounded px-2 py-1 text-sm" />
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Impor
                        JSON</button>
                </form>
                <a href="{{ route('products.export-json') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ekspor JSON</a>
            </div> --}}
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('products.index') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari kode/nama produk..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category" id="category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-4">
                            <button type="submit"
                                class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                                Filter
                            </button>
                            @if(request('search') || request('category'))
                                <a href="{{ route('products.index') }}"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                                <tr>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Kode</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Kategori</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Stok</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 transition-all">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $product->code }}</td>
                                        <td class="px-4 py-2 border">{{ $product->name }}</td>
                                        <td class="px-4 py-2 border">{{ $product->category->name }}</td>
                                        <td class="px-4 py-2 border">
                                            <div class="flex items-center space-x-2">
                                                <span>{{ $product->formatted_stock }}</span>
                                                @if($product->needs_restock)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Stok Rendah
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 border text-sm font-medium space-x-2">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="text-blue-600 hover:text-blue-900">Detail</a>
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-green-600 hover:text-green-900">Edit</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="text-red-600 hover:text-red-900 delete-confirm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 border text-center text-gray-500">Tidak ada data produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 flex justify-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>