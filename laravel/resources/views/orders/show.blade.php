<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pesanan') }} - {{ $order->order_number }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('orders.edit', $order) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Ubah Pesanan
                </a>
                <a href="{{ route('orders.invoice', $order) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" target="_blank">
                    Cetak Faktur
                </a>
                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Pesanan</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Nomor Pesanan:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->order_number }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status === 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->created_at->format('d M Y H:i:s') }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Ambil:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->pickup_date->format('d M Y H:i') }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Metode Pengiriman:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->delivery_method_label }}</dd>
                                </div>
                                @if($order->delivery_method !== 'pickup')
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Alamat Pengiriman:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->delivery_address }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Pelanggan</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Nama Pelanggan:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->customer ? $order->customer->name : '[Pelanggan Dihapus]' }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Kontak Pelanggan:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $order->customer ? $order->customer->phone : '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Item Pesanan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Harga</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucfirst(str_replace('_', ' ', $item->price_type)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($item->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ $item->qty }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Subtotal:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($order->delivery_fee > 0)
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Biaya Pengiriman:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Total + Ongkir:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Rp {{ number_format($order->total + $order->delivery_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Uang Muka:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-green-600">Rp {{ number_format($order->down_payment, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">Sisa Pembayaran:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-blue-600">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
