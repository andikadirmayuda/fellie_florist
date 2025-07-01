<x-app-layout>
    <x-slot name="header">
        Detail Pesanan Publik
    </x-slot>
    <div class="container mx-auto py-8">
        <div class="mb-4">
            <a href="{{ route('admin.public-orders.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke daftar pesanan</a>
        </div>
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-2">Informasi Pelanggan</h2>
            <div class="mb-2">Nama: <b>{{ $order->customer_name }}</b></div>
            <div class="mb-2">No. WhatsApp: <b>{{ $order->wa_number }}</b></div>
            <div class="mb-2">Tanggal Ambil/Kirim: <b>{{ $order->pickup_date }}</b></div>
            <div class="mb-2">Waktu Ambil/Pengiriman: <b>{{ $order->pickup_time }}</b></div>
            <div class="mb-2">Metode Pengiriman: <b>{{ $order->delivery_method }}</b></div>
            <div class="mb-2">Tujuan Pengiriman: <b>{{ $order->destination }}</b></div>
            <div class="mb-2">Status: <b>{{ ucfirst($order->status) }}</b></div>
        </div>
        <div class="bg-white rounded shadow p-6">
        <div class="mb-6 text-center">
            @if(!empty($order->public_code))
                <a href="{{ route('public.order.invoice', ['public_code' => $order->public_code]) }}" target="_blank" class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded mb-2">
                    <i class="bi bi-receipt mr-1"></i>Lihat Invoice Publik
                </a>
                {{-- <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->wa_number) }}?text={{ urlencode('Berikut link invoice pesanan Anda di Fellie Florist: ' . route('public.order.invoice', ['public_code' => $order->public_code])) }}" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="bi bi-whatsapp mr-1"></i>Share Invoice ke WhatsApp
                </a> --}}
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->wa_number)) }}?text={{ urlencode('Terima kasih telah memesan di Fellie Florist! Berikut link invoice pesanan Anda: ' . url()->current()) }}" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="bi bi-whatsapp mr-1"></i>Kirim Invoice ke WhatsApp
                </a>
            @else
                <div class="text-red-600 font-semibold">Kode invoice publik belum tersedia. Silakan edit/migrasi data order ini.</div>
            @endif
        </div>
            <h2 class="text-lg font-semibold mb-2">Produk Dipesan</h2>
            <table class="min-w-full bg-white border border-gray-200 mb-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Nama Produk</th>
                        <th class="px-4 py-2 border">Tipe Harga</th>
                        <th class="px-4 py-2 border">Harga Satuan</th>
                        <th class="px-4 py-2 border">Satuan</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-2 border">{{ $item->product_name }}</td>
                        <td class="px-4 py-2 border">{{ $item->price_type ?? '-' }}</td>
                        <td class="px-4 py-2 border">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border">{{ $item->unit_equivalent ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 border">Rp{{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
