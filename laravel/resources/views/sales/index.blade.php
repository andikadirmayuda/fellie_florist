<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Daftar Transaksi Penjualan
        </h2>
    </x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4">
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Data Penjualan</h3>
            <a href="{{ route('sales.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">Transaksi Baru</a>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">No. Penjualan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Metode Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sales as $sale)
                    <tr>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-indigo-700 dark:text-indigo-300 font-semibold">{{ $sale->order_number }}</td>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $sale->order_time }}</td>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ ucfirst($sale->payment_method) }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('sales.show', $sale->id) }}" class="text-blue-600 hover:underline text-sm font-medium">Detail</a>
                            <a href="{{ route('sales.show', $sale->id) }}?print=1" class="text-yellow-600 hover:underline text-sm font-medium">Print Struk</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-300">Belum ada transaksi penjualan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-end">
            {{ $sales->links() }}
        </div>
    </div>
</x-app-layout>
