<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan Publik - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-pink-600">Fellie Florist</h1>
                <p class="text-gray-600">Invoice Pemesanan Publik</p>
            </div>

            <div class="mb-6 border-b pb-2">
                <div class="mb-1">Nama: <b>{{ $order->customer_name }}</b></div>
                <div class="mb-1">No. WhatsApp: <b>{{ $order->wa_number }}</b></div>
                <div class="mb-1">Tanggal Ambil/Kirim: <b>{{ $order->pickup_date }}</b></div>
                <div class="mb-1">Waktu Ambil/Pengiriman: <b>{{ $order->pickup_time }}</b></div>
                <div class="mb-1">Metode Pengiriman: <b>{{ $order->delivery_method }}</b></div>
                <div class="mb-1">Tujuan Pengiriman: <b>{{ $order->destination }}</b></div>
                <div class="mb-1">Status: <b>{{ ucfirst($order->status) }}</b></div>
            </div>

            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="py-2 px-4">Nama Produk</th>
                            <th class="py-2 px-4">Tipe Harga</th>
                            <th class="py-2 px-4 text-right">Harga Satuan</th>
                            <th class="py-2 px-4 text-right">Satuan</th>
                            <th class="py-2 px-4 text-right">Jumlah</th>
                            <th class="py-2 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @php $total = 0; @endphp
                        @foreach($order->items as $item)
                        @php $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0); $total += $subtotal; @endphp
                        <tr>
                            <td class="py-2 px-4">{{ $item->product_name }}</td>
                            <td class="py-2 px-4">{{ $item->price_type ?? '-' }}</td>
                            <td class="py-2 px-4 text-right">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-right">{{ $item->unit_equivalent ?? '-' }}</td>
                            <td class="py-2 px-4 text-right">{{ $item->quantity }}</td>
                            <td class="py-2 px-4 text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right px-4 py-2">Total</th>
                            <th class="px-4 py-2 text-right">Rp{{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>


            <div class="text-center mt-6">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->wa_number) }}?text={{ urlencode('Terima kasih telah memesan di Fellie Florist! Berikut link invoice pesanan Anda: ' . url()->current()) }}" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="bi bi-whatsapp mr-1"></i>Kirim Invoice ke WhatsApp
                </a>
            </div>

            <div class="text-center text-gray-600 text-sm mt-8">
                <p>Terima kasih telah memesan di Fellie Florist!</p>
                <p class="mt-2">Jika ada pertanyaan, silakan hubungi admin kami.</p>
                <p class="mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4 print:hidden">
        <button onclick="window.print()" class="bg-pink-600 text-white px-4 py-2 rounded-lg shadow hover:bg-pink-700">
            Print Invoice
        </button>
    </div>
</body>
</html>
