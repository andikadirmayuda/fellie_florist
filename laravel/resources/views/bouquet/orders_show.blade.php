<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4 text-pink-600">Detail Pemesanan Buket</h1>
        <div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="mb-2 text-gray-500 text-xs">No. Pesanan</div>
                    <div class="font-semibold text-lg text-gray-800">{{ $order->order_number ?? '-' }}</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">Status</div>
                    <div class="font-semibold text-gray-800">{{ ucfirst($order->status) }}</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">Nama Pemesan</div>
                    <div class="text-gray-800">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">No. WA</div>
                    <div class="text-gray-800">{{ $order->wa_number }}</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">Tanggal Order</div>
                    <div class="text-gray-800">{{ $order->created_at->format('d-m-Y H:i') }}</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">Metode Pengiriman</div>
                    <div class="text-gray-800">{{ $order->delivery_method ?? '-' }} @if($order->delivery_note) <span class="text-xs text-gray-400">({{ $order->delivery_note }})</span> @endif</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">Tanggal & Waktu Pengiriman</div>
                    <div class="text-gray-800">{{ $order->delivery_at ? \Carbon\Carbon::parse($order->delivery_at)->format('d-m-Y H:i') : '-' }}</div>
                </div>
                <div>
                    <div class="mb-2 text-gray-500 text-xs">Tanggal & Waktu Pengambilan</div>
                    <div class="text-gray-800">{{ $order->pickup_at ? \Carbon\Carbon::parse($order->pickup_at)->format('d-m-Y H:i') : '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="mb-2 text-gray-500 text-xs">Catatan</div>
                    <div class="text-gray-800">{{ $order->notes ?: '-' }}</div>
                </div>
                <div class="md:col-span-2 text-right mt-4">
                    <span class="text-gray-500 text-xs">Total</span>
                    <span class="text-xl font-bold text-pink-600 ml-2">Rp{{ number_format($order->total_price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
            <h2 class="font-semibold mb-4 text-pink-600">Komposisi Bunga</h2>
            <table class="min-w-full text-sm border-separate border-spacing-y-2">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left text-gray-500 font-medium">Bunga</th>
                        <th class="px-3 py-2 text-left text-gray-500 font-medium">Jumlah</th>
                        <th class="px-3 py-2 text-left text-gray-500 font-medium">Harga Satuan</th>
                        <th class="px-3 py-2 text-left text-gray-500 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="bg-white hover:bg-pink-50 transition">
                        <td class="px-3 py-2 text-gray-800">{{ $item->product->name }}</td>
                        <td class="px-3 py-2 text-gray-800">{{ $item->quantity }}</td>
                        <td class="px-3 py-2 text-gray-800">Rp{{ number_format($item->price,0,',','.') }}</td>
                        <td class="px-3 py-2 text-gray-800">Rp{{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('bouquet.orders.index') }}" class="mt-6 inline-block text-pink-600 hover:underline">&larr; Kembali ke daftar</a>
    </div>
</x-app-layout>
