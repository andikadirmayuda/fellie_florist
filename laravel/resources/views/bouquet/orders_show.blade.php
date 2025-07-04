<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4 text-pink-600">Detail Pemesanan Buket</h1>
        <div class="bg-white rounded shadow p-6 mb-4">
            <div><b>Nama:</b> {{ $order->customer_name }}</div>
            <div><b>No. WA:</b> {{ $order->wa_number }}</div>
            <div><b>Tanggal:</b> {{ $order->created_at->format('d-m-Y H:i') }}</div>
            <div><b>Catatan:</b> {{ $order->notes }}</div>
            <div><b>Total:</b> Rp{{ number_format($order->total_price,0,',','.') }}</div>
        </div>
        <div class="bg-white rounded shadow p-6">
            <h2 class="font-semibold mb-2">Komposisi Bunga:</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">Bunga</th>
                        <th class="border px-2 py-1">Jumlah</th>
                        <th class="border px-2 py-1">Harga Satuan</th>
                        <th class="border px-2 py-1">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="border px-2 py-1">{{ $item->product->name }}</td>
                        <td class="border px-2 py-1">{{ $item->quantity }}</td>
                        <td class="border px-2 py-1">Rp{{ number_format($item->price,0,',','.') }}</td>
                        <td class="border px-2 py-1">Rp{{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('bouquet.orders.index') }}" class="mt-4 inline-block text-pink-600">&larr; Kembali ke daftar</a>
    </div>
</x-app-layout>
