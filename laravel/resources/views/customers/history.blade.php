<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pemesanan: {{ $customer->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <a href="{{ route('customers.index') }}" class="mb-4 inline-block bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Kembali ke Daftar Pelanggan</a>
                @if($orders->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($order->status) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($order->total + $order->delivery_fee, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">Belum ada riwayat pemesanan untuk pelanggan ini.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
