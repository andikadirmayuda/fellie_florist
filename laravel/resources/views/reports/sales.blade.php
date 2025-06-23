<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">Laporan Penjualan</h2>
            <form method="GET" class="flex flex-wrap gap-2 items-end">
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-300">Dari</label>
                    <input type="date" name="start_date" value="{{ $start }}" class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-300">Sampai</label>
                    <input type="date" name="end_date" value="{{ $end }}" class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                </div>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded shadow">Filter</button>
                <a href="{{ route('reports.sales.pdf', request()->all()) }}" class="bg-white border border-black text-black px-4 py-2 rounded shadow ml-2">Export PDF</a>
            </form>
        </div>
    </x-slot>
    <div class="flex min-h-screen">
        <div class="flex-1 p-4 md:p-8 bg-white dark:bg-gray-900">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="rounded-xl p-5 bg-black text-white border border-gray-800">
                    <div class="text-xs mb-1 text-gray-300">Total Pendapatan</div>
                    <div class="text-2xl font-bold">Rp{{ number_format($totalPendapatan,0,',','.') }}</div>
                    <div class="text-xs mt-2 text-gray-400">Periode: {{ date('d M Y', strtotime($start)) }} - {{ date('d M Y', strtotime($end)) }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Total Transaksi</div>
                    <div class="text-2xl font-bold">{{ $totalTransaksi }}</div>
                    <div class="text-xs mt-2 text-gray-400">Transaksi sukses</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Produk Terlaris</div>
                    @if($produkTerlaris)
                        <div class="font-bold text-lg">{{ $produkTerlaris->name }}</div>
                        <div class="text-sm">Terjual: <span class="font-semibold">{{ $produkTerlaris->total_terjual }}</span></div>
                        <div class="text-xs mt-2 text-gray-400">Harga: Rp{{ number_format($produkTerlaris->price,0,',','.') }}</div>
                    @else
                        <div class="text-gray-300">Tidak ada data</div>
                    @endif
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Produk Penjualan Terendah</div>
                    @if($produkKurangLaku)
                        <div class="font-bold text-lg">{{ $produkKurangLaku->name }}</div>
                        <div class="text-sm">Terjual: <span class="font-semibold">{{ $produkKurangLaku->total_terjual }}</span></div>
                        <div class="text-xs mt-2 text-gray-400">Harga: Rp{{ number_format($produkKurangLaku->price,0,',','.') }}</div>
                    @else
                        <div class="text-gray-300">Tidak ada data</div>
                    @endif
                </div>
            </div>
            <!-- Tabel Transaksi -->
            <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">No. Penjualan</th>
                            <th class="px-4 py-2 text-left">Tanggal & Waktu Pemesanan</th>
                            <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-semibold">{{ $sale->order_number }}</td>
                            <td class="px-4 py-2">{{ $sale->order_time ? \Carbon\Carbon::parse($sale->order_time)->format('d-m-Y H:i') : '-' }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $badge = match($sale->payment_method) {
                                        'cash' => 'bg-black text-white',
                                        'debit' => 'bg-white text-black border border-black',
                                        'transfer' => 'bg-gray-800 text-white',
                                        default => 'bg-gray-200 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst($sale->payment_method ?? '-') }}
                                </span>
                            </td>
                            <td class="px-4 py-2">Rp{{ number_format($sale->total,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-400">Tidak ada data penjualan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
