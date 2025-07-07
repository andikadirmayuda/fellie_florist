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
            <div class="mb-4 sm:mb-6 border-b pb-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Invoice untuk:</span> <b>{{ $order->customer_name }}</b></div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">No. WhatsApp:</span> <b>{{ $order->wa_number }}</b></div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Tanggal Ambil/Kirim:</span> <b>{{ $order->pickup_date }}</b></div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Waktu Ambil/Pengiriman:</span> <b>{{ $order->pickup_time }}</b></div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Metode Pengiriman:</span> <b>{{ $order->delivery_method }}</b></div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Tujuan Pengiriman:</span> <b>{{ $order->destination }}</b></div>
                </div>
                <div class="sm:text-right">
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Status Pesanan:</span> <b>{{ ucfirst($order->status) }}</b></div>
                    <div class="mb-0.5 text-xs sm:text-base"><span class="font-semibold">Status Pembayaran:</span> <b>{{ $order->payment_status ?? '-' }}</b></div>
                    @php
                        $total = 0;
                        foreach($order->items as $item) {
                            $total += ($item->price ?? 0) * ($item->quantity ?? 0);
                        }
                    @endphp
                </div>
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
                    @php $total_paid = $order->total_paid ?? ($order->payments ? $order->payments->sum('amount') : 0); @endphp
                    @if($total_paid > 0)
                    <tr>
                        <th colspan="5" class="text-right px-0.5 py-1 sm:px-4 sm:py-2">Total Sudah Dibayar</th>
                        <th class="px-0.5 py-1 sm:px-4 sm:py-2 text-right">Rp{{ number_format($total_paid, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right px-0.5 py-1 sm:px-4 sm:py-2">Sisa Pembayaran</th>
                        <th class="px-0.5 py-1 sm:px-4 sm:py-2 text-right">Rp{{ number_format(max($total - $total_paid, 0), 0, ',', '.') }}</th>
                    </tr>
                    @endif
                </tfoot>

            </table>

            {{-- Bukti Pembayaran --}}
            @if(!empty($order->payment_proof))
                <div class="my-8 text-center">
                    <h3 class="font-semibold text-base mb-2 flex items-center gap-2 justify-center"><i class="bi bi-receipt"></i> Bukti Pembayaran</h3>
                    @php
                        $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                        <img src="{{ asset('storage/' . $order->payment_proof) }}"
                             alt="Bukti Pembayaran"
                             class="mx-auto rounded shadow max-h-64 border mb-2"
                             style="max-width:300px;"
                             onerror="this.style.display='none'; document.getElementById('payment-proof-error').style.display='block';" />
                    @elseif(strtolower($ext) == 'pdf')
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-blue-600 underline">Lihat Bukti Pembayaran (PDF)</a>
                    @else
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-blue-600 underline">Download Bukti Pembayaran</a>
                    @endif
                    <div id="payment-proof-error" style="display:none; color:red;">Bukti pembayaran tidak ditemukan di server.</div>
                </div>
            @endif

            {{-- Riwayat Pembayaran dihapus sesuai permintaan --}}
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
