<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Detail Penjualan
        </h2>
    </x-slot>
    <div class="max-w-lg mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="mb-4">
                <span class="block text-sm text-gray-500 dark:text-gray-300">No. Penjualan</span>
                <span class="text-lg font-bold text-indigo-700 dark:text-indigo-300">{{ $sale->order_number }}</span>
            </div>
            <div class="mb-2">
                <span class="block text-sm text-gray-500 dark:text-gray-300">Waktu Pemesanan</span>
                <span class="text-gray-900 dark:text-gray-100">{{ $sale->order_time }}</span>
            </div>
            <div class="mb-2">
                <span class="block text-sm text-gray-500 dark:text-gray-300">Metode Pembayaran</span>
                <span class="text-gray-900 dark:text-gray-100">{{ ucfirst($sale->payment_method) }}</span>
            </div>
            <div class="my-4">
                <span class="block text-sm text-gray-500 dark:text-gray-300 mb-1">Daftar Produk</span>
                <ul class="list-disc ml-5">
                    @foreach($sale->items as $item)
                        <li class="mb-1 text-gray-900 dark:text-gray-100">
                            {{ $item->product->name }} ({{ $item->price_type }}) x {{ $item->quantity }} = {{ number_format($item->subtotal, 0, ',', '.') }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-between items-center border-t pt-4 mt-4">
                <span class="font-semibold text-gray-700 dark:text-gray-200">Total</span>
                <span class="font-bold text-lg text-indigo-700 dark:text-indigo-300">{{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('sales.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Kembali</a>
                <a href="{{ route('sales.show', $sale->id) }}?print=1" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded" target="_blank">Print Struk</a>
            </div>
        </div>
    </div>
</x-app-layout>
