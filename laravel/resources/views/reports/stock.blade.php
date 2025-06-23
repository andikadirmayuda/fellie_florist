<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Laporan Stok Produk
        </h2>
    </x-slot>
    <div class="py-4 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white">
        <form method="GET" class="flex flex-wrap gap-2 mb-4 items-end">
            <div>
                <label class="block text-sm text-black">Dari</label>
                <input type="date" name="start_date" value="{{ $start ?? '' }}" class="border border-black rounded px-2 py-1 bg-white text-black">
            </div>
            <div>
                <label class="block text-sm text-black">Sampai</label>
                <input type="date" name="end_date" value="{{ $end ?? '' }}" class="border border-black rounded px-2 py-1 bg-white text-black">
            </div>
            <button type="submit" class="flex items-center gap-2 bg-black text-white px-4 py-2 rounded shadow-black shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Filter
            </button>
        </form>
        <!-- Cards Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-black shadow-md flex items-center gap-3">
                <span class="bg-black text-white rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg></span>
                <div>
                    <div class="text-xs text-black mb-1">Total Produk</div>
                    <div class="text-2xl font-bold text-black">{{ $products->count() }}</div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-black shadow-md flex items-center gap-3">
                <span class="bg-black text-white rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg></span>
                <div>
                    <div class="text-xs text-black mb-1">Total Stok Masuk</div>
                    <div class="text-2xl font-bold text-black">{{ $products->sum(fn($p) => $rekap[$p->id]['masuk'] ?? 0) }}</div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-black shadow-md flex items-center gap-3">
                <span class="bg-black text-white rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <div>
                    <div class="text-xs text-black mb-1">Total Stok Keluar</div>
                    <div class="text-2xl font-bold text-black">{{ $products->sum(fn($p) => $rekap[$p->id]['keluar'] ?? 0) }}</div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-black shadow-md flex items-center gap-3">
                <span class="bg-black text-white rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg></span>
                <div>
                    <div class="text-xs text-black mb-1">Total Penyesuaian</div>
                    <div class="text-2xl font-bold text-black">{{ $products->sum(fn($p) => $rekap[$p->id]['penyesuaian'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <!-- Tabel Rekap Stok -->
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border text-sm bg-white text-black shadow-black shadow-md rounded-xl" style="border-collapse:collapse;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Produk</th>
                        <th class="border px-2 py-1">Kategori</th>
                        <th class="border px-2 py-1">Stok Masuk</th>
                        <th class="border px-2 py-1">Stok Keluar</th>
                        <th class="border px-2 py-1">Penyesuaian</th>
                        <th class="border px-2 py-1">Stok Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="border px-2 py-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            {{ $product->name }}
                        </td>
                        <td class="border px-2 py-1">{{ $product->category->name ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $rekap[$product->id]['masuk'] ?? 0 }}</td>
                        <td class="border px-2 py-1">{{ $rekap[$product->id]['keluar'] ?? 0 }}</td>
                        <td class="border px-2 py-1">{{ $rekap[$product->id]['penyesuaian'] ?? 0 }}</td>
                        <td class="border px-2 py-1">{{ $rekap[$product->id]['stok_akhir'] ?? $product->current_stock }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <h2 class="text-lg font-semibold mb-2 text-black flex items-center gap-2">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Log Perubahan Stok (Terbaru)
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm bg-white text-black shadow-black shadow-md rounded-xl" style="border-collapse:collapse;">
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
                        <td class="border px-2 py-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $log->created_at->format('d-m-Y H:i') }}
                        </td>
                        <td class="border px-2 py-1">{{ $log->product->name ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $log->qty }}</td>
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
