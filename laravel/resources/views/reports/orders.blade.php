<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">Laporan Pemesanan</h2>
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
            </form>
        </div>
    </x-slot>
    <div class="flex min-h-screen">
        <div class="flex-1 p-4 md:p-8 bg-white dark:bg-gray-900">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="rounded-xl p-5 bg-black text-white border border-gray-800">
                    <div class="text-xs mb-1 text-gray-300">Total Pesanan</div>
                    <div class="text-2xl font-bold">{{ $totalOrder }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Total Nominal</div>
                    <div class="text-2xl font-bold">Rp{{ number_format($totalNominal,0,',','.') }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Pesanan Selesai</div>
                    <div class="text-2xl font-bold">{{ $totalLunas }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Belum Selesai</div>
                    <div class="text-2xl font-bold">{{ $totalBelumLunas }}</div>
                </div>
            </div>
            <!-- Tabel Pemesanan -->
            <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">No. Order</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Pelanggan</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-semibold">{{ $order->order_number }}</td>
                            <td class="px-4 py-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $order->customer ? $order->customer->name : '-' }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $badge = match($order->status) {
                                        'completed' => 'bg-black text-white',
                                        'pending' => 'bg-gray-200 text-gray-800',
                                        'processed' => 'bg-gray-800 text-white',
                                        'cancelled' => 'bg-red-600 text-white',
                                        default => 'bg-gray-200 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">Rp{{ number_format($order->total + $order->delivery_fee,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-400">Tidak ada data pemesanan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
