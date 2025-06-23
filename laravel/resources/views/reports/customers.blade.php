<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">Laporan Pelanggan</h2>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="rounded-xl p-5 bg-black text-white border border-gray-800">
                    <div class="text-xs mb-1 text-gray-300">Total Pelanggan</div>
                    <div class="text-2xl font-bold">{{ $totalCustomer }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Total Order</div>
                    <div class="text-2xl font-bold">{{ $totalOrder }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Pelanggan Terbaik</div>
                    @if($topCustomer)
                        <div class="font-bold text-lg">{{ $topCustomer->name }}</div>
                        <div class="text-sm">Total Belanja: <span class="font-semibold">Rp{{ number_format($topCustomer->orders->sum('total'),0,',','.') }}</span></div>
                    @else
                        <div class="text-gray-300">-</div>
                    @endif
                </div>
            </div>
            <!-- Tabel Pelanggan -->
            <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Kontak</th>
                            <th class="px-4 py-2 text-left">Jumlah Order</th>
                            <th class="px-4 py-2 text-left">Total Belanja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-semibold">{{ $customer->name }}</td>
                            <td class="px-4 py-2">{{ $customer->phone }}</td>
                            <td class="px-4 py-2">{{ $customer->orders_count }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($customer->orders->sum('total'),0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-400">Tidak ada data pelanggan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
