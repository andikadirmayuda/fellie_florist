<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Penjualan</h2>
    </x-slot>
    <div class="py-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form method="GET" class="flex flex-wrap gap-2 mb-4 items-end">
            <div>
                <label class="block text-sm">Dari</label>
                <input type="date" name="start_date" value="{{ $start }}" class="border rounded px-2 py-1">
            </div>
            <div>
                <label class="block text-sm">Sampai</label>
                <input type="date" name="end_date" value="{{ $end }}" class="border rounded px-2 py-1">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('reports.sales.pdf', request()->all()) }}" class="bg-red-600 text-white px-4 py-2 rounded ml-2">Export PDF</a>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded shadow p-4 flex items-center">
                <div class="flex-1">
                    <div class="text-gray-500 text-xs">Total Pendapatan</div>
                    <div class="text-2xl font-bold text-green-600">Rp{{ number_format($totalPendapatan,0,',','.') }}</div>
                </div>
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="bg-white rounded shadow p-4 flex items-center">
                <div class="flex-1">
                    <div class="text-gray-500 text-xs">Total Transaksi</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $totalTransaksi }}</div>
                </div>
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M9 3v18m6-18v18M4 21h16a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v16a1 1 0 001 1z"/></svg>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm bg-white text-gray-900" style="border-collapse:collapse;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">No. Penjualan</th>
                        <th class="border px-2 py-1">Tanggal & Waktu Pemesanan</th>
                        <th class="border px-2 py-1">Metode Pembayaran</th>
                        <th class="border px-2 py-1">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="border px-2 py-1">{{ $sale->order_number }}</td>
                        <td class="border px-2 py-1">{{ $sale->order_time ? \Carbon\Carbon::parse($sale->order_time)->format('d-m-Y H:i') : '-' }}</td>
                        <td class="border px-2 py-1">{{ $sale->payment_method ?? '-' }}</td>
                        <td class="border px-2 py-1">Rp{{ number_format($sale->total,0,',','.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada data penjualan pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
