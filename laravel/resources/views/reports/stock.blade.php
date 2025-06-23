<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Stok Produk</h2>
    </x-slot>
    <div class="py-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border text-sm bg-white text-gray-900" style="border-collapse:collapse;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Produk</th>
                        <th class="border px-2 py-1">Kategori</th>
                        <th class="border px-2 py-1">Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="border px-2 py-1">{{ $product->name }}</td>
                        <td class="border px-2 py-1">{{ $product->category->name ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $product->stock }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">Tidak ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <h2 class="text-lg font-semibold mb-2">Log Perubahan Stok (Terbaru)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm bg-white text-gray-900" style="border-collapse:collapse;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Tanggal</th>
                        <th class="border px-2 py-1">Produk</th>
                        <th class="border px-2 py-1">Perubahan</th>
                        <th class="border px-2 py-1">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="border px-2 py-1">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border px-2 py-1">{{ $log->product->name ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $log->change }}</td>
                        <td class="border px-2 py-1">{{ $log->description }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada log perubahan stok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
