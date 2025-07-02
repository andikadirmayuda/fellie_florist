<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Inventaris') }}
            </h2>
            <x-primary-button onclick="window.location.href='{{ route('inventory.adjust.form') }}'">
                <i class="bi bi-pencil-square mr-2"></i> Sesuaikan Stok
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Dashboard Ringkasan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded shadow">
                            <div class="text-sm text-gray-500">Total Produk</div>
                            <div class="text-2xl font-bold">{{ $products->count() }}</div>
                        </div>
                        <div class="bg-green-100 p-4 rounded shadow">
                            <div class="text-sm text-gray-500">Total Aktivitas Stok</div>
                            <div class="text-2xl font-bold">{{ number_format($logs->total()) }}</div>
                        </div>
                        <div class="bg-red-100 p-4 rounded shadow">
                            <div class="text-sm text-gray-500">Stok Menipis</div>
                            <div class="text-2xl font-bold">{{ $products->where('needs_restock', true)->count() }}</div>
                        </div>
                    </div>

                    <!-- Tabel Riwayat Aktivitas Stok -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Riwayat Aktivitas Stok Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        {{-- <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($logs as $log)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $log->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->product->name ?? '-' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $log->product->category->name ?? '-' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                                            @if($log->qty > 0)
                                                <span class="text-green-600 font-semibold">Masuk</span>
                                            @elseif($log->qty < 0)
                                                <span class="text-red-600 font-semibold">Keluar</span>
                                            @else
                                                <span class="text-gray-500">Penyesuaian</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ number_format(abs($log->qty)) }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $log->product->base_unit ?? '-' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($log->source) }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $log->notes }}</td>
                                        {{-- <td class="px-4 py-2 whitespace-nowrap text-sm text-blue-600 hover:underline cursor-pointer">
                                            <button type="button" @click="$dispatch('open-adjust-modal'); $nextTick(() => { category_id = '{{ $log->product->category->id ?? '' }}'; product_id = '{{ $log->product->id ?? '' }}'; onProductChange(); })">
                                                Sesuaikan Stok
                                            </button>
                                        </td> --}}
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-2 text-center text-gray-400">Belum ada aktivitas stok.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    </div>

                </div> <!-- penutup div p-6 text-gray-900 -->
            </div> <!-- penutup div bg-white ... -->
        </div> <!-- penutup div max-w-7xl ... -->

        <!-- Daftar Stok Produk Saat Ini (TERPISAH) -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Daftar Stok Produk Saat Ini</h3>
                    <!-- Filter Form -->
                    <form method="GET" action="" class="mb-4 flex flex-col md:flex-row md:items-end gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari Kode/Nama</label>
                            <input type="text" name="search" id="search" value="{{ $filter_search ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Cari kode/nama produk...">
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($categoriesWithProducts as $cat)
                                    <option value="{{ $cat->id }}" @if(($filter_category ?? '') == $cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">Filter</button>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $product->code }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $product->category->name ?? '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $product->formatted_stock }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $product->base_unit }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-blue-600 hover:underline cursor-pointer">
                                        <a href="{{ route('inventory.adjust-form', $product) }}">Sesuaikan Stok</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
