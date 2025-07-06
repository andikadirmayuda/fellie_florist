<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan Publik - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-1 sm:px-4 py-2 sm:py-8 flex justify-center items-center min-h-screen text-[13px] sm:text-base">
        <div class="bg-white rounded-lg shadow-lg p-1 sm:p-6 w-full max-w-[95vw] sm:max-w-2xl mx-auto">
            <div class="text-center mb-4 sm:mb-8">
                <h1 class="text-xl sm:text-3xl font-bold text-pink-600">Fellie Florist</h1>
                <p class="text-gray-600 text-xs sm:text-base">Invoice Pemesanan Publik</p>
            </div>
            <div class="mb-4 sm:mb-6 border-b pb-2">
                <div class="mb-0.5 text-xs sm:text-base">Nama: <b>{{ $order->customer_name }}</b></div>
                <div class="mb-0.5 text-xs sm:text-base">No. WhatsApp: <b>{{ $order->wa_number }}</b></div>
                <div class="mb-0.5 text-xs sm:text-base">Tanggal Ambil/Kirim: <b>{{ $order->pickup_date }}</b></div>
                <div class="mb-0.5 text-xs sm:text-base">Waktu Ambil/Pengiriman: <b>{{ $order->pickup_time }}</b></div>
                <div class="mb-0.5 text-xs sm:text-base">Metode Pengiriman: <b>{{ $order->delivery_method }}</b></div>
                <div class="mb-0.5 text-xs sm:text-base">Tujuan Pengiriman: <b>{{ $order->destination }}</b></div>
                <div class="mb-0.5 text-xs sm:text-base">Status: <b>{{ ucfirst($order->status) }}</b></div>
            </div>
            <h2 class="text-base sm:text-lg font-semibold mb-1 sm:mb-2">Produk Dipesan</h2>
            <table class="w-full mb-4 sm:mb-8 text-[10px] sm:text-base table-fixed">
                <thead>
                    <tr class="text-left bg-gray-50">
                        <th class="py-1 px-0.5 sm:py-2 sm:px-4 w-2/12 whitespace-normal">Nama</th>
                        <th class="py-1 px-0.5 sm:py-2 sm:px-4 w-2/12 whitespace-normal">Tipe</th>
                        <th class="py-1 px-0.5 sm:py-2 sm:px-4 w-2/12 text-right whitespace-normal">Harga</th>
                        <th class="py-1 px-0.5 sm:py-2 sm:px-4 w-2/12 text-right whitespace-normal">Satuan</th>
                        <th class="py-1 px-0.5 sm:py-2 sm:px-4 w-2/12 text-right whitespace-normal">Jumlah</th>
                        <th class="py-1 px-0.5 sm:py-2 sm:px-4 w-2/12 text-right whitespace-normal">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @php $total = 0; @endphp
                    @foreach($order->items as $item)
                    @php $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0); $total += $subtotal; @endphp
                    <tr>
                        <td class="py-1 px-0.5 sm:py-2 sm:px-4 break-words whitespace-normal align-top">{{ $item->product_name }}</td>
                        <td class="py-1 px-0.5 sm:py-2 sm:px-4 break-words whitespace-normal align-top">{{ $item->price_type ?? '-' }}</td>
                        <td class="py-1 px-0.5 sm:py-2 sm:px-4 text-right align-top">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                        <td class="py-1 px-0.5 sm:py-2 sm:px-4 text-right align-top">{{ $item->unit_equivalent ?? '-' }}</td>
                        <td class="py-1 px-0.5 sm:py-2 sm:px-4 text-right align-top">{{ $item->quantity }}</td>
                        <td class="py-1 px-0.5 sm:py-2 sm:px-4 text-right align-top">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right px-0.5 py-1 sm:px-4 sm:py-2">Total</th>
                        <th class="px-0.5 py-1 sm:px-4 sm:py-2 text-right">Rp{{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            {{-- Form update pembayaran untuk pengguna --}}
            @if(!in_array($order->payment_status, ['paid']) && !in_array($order->status, ['cancelled','completed','done']))
            <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4">
                <form method="POST" action="{{ route('public.order.pay', $order->public_code) }}" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-2 items-center">
                        <label class="font-semibold">Status Pembayaran:</label>
                        <select name="payment_type" class="border rounded p-1" required>
                            <option value="dp">DP (Uang Muka)</option>
                            <option value="lunas">Lunas</option>
                        </select>
                        <input type="number" name="amount" class="border rounded p-1" placeholder="Nominal (Rp)" min="1" required>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 items-center">
                        <label class="font-semibold">Upload Bukti Pembayaran:</label>
                        <input type="file" name="payment_proof" accept="image/*,application/pdf" class="border rounded p-1" required>
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Kirim Pembayaran</button>
                </form>
            </div>
            @endif
                </tfoot>
            </table>
            {{-- <div class="text-center mt-6">
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->wa_number)) }}?text={{ urlencode('Terima kasih telah memesan di Fellie Florist! Berikut link invoice pesanan Anda: ' . url()->current()) }}" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="bi bi-whatsapp mr-1"></i>Kirim Invoice ke WhatsApp
                </a>
            </div> --}}
            <div class="text-center text-gray-600 text-xs sm:text-sm mt-4 sm:mt-8">
                <p>Terima kasih telah memesan di Fellie Florist!</p>
                <p class="mt-1 sm:mt-2">Jika ada pertanyaan, silakan hubungi admin kami.</p>
                <p class="mt-4">
                    @if($order->status === 'pending' && config('public_order.enable_public_order_edit'))
                        <a href="{{ route('public.order.edit', ['public_code' => $order->public_code]) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Edit Pesanan</a>
                    @endif
                </p>
                <p class="mt-2 sm:mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
    <div class="fixed bottom-2 right-2 sm:bottom-4 sm:right-4 print:hidden z-50">
        <button onclick="window.print()" class="bg-pink-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow hover:bg-pink-700 text-xs sm:text-base">
            Print Invoice
        </button>
    </div>
</body>
</html>
