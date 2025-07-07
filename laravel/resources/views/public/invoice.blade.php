<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan Publik - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <div class="flex justify-center items-center min-h-screen py-4 px-2">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-auto p-6 sm:p-10 border border-gray-200">
            <!-- Header Invoice -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-2 border-b pb-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-pink-600 tracking-tight leading-tight">Fellie Florist</h1>
                    <p class="text-gray-500 text-sm font-medium">INVOICE PEMESANAN</p>
                </div>
                <div class="text-sm text-gray-400 mt-2 sm:mt-0 text-right">
                    <div>Tanggal Cetak: {{ date('d/m/Y H:i') }}</div>
                    @php
                        // Format: INVOICE-001-070725-{{ $order->id }}
                        $noUrut = str_pad($order->id, 3, '0', STR_PAD_LEFT);
                        $tgl = date('dmy', strtotime($order->created_at ?? now()));
                        $noInvoice = '#INV-' . $noUrut . '' . $tgl . '' . $order->id;
                    @endphp
                    <div class="mt-1">No. Invoice: <span class="font-semibold text-gray-700">{{ $noInvoice }}</span></div>
                </div>
            </div>
            <!-- Info Pelanggan & Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div class="space-y-1.5">
                    <div><span class="text-gray-500">Nama Pemesan</span><br><span class="font-semibold text-gray-800 text-base">{{ $order->customer_name }}</span></div>
                    <div><span class="text-gray-500">No. WhatsApp</span><br><span class="font-semibold text-gray-800">{{ $order->wa_number }}</span></div>
                    <div><span class="text-gray-500">Tanggal Ambil/Kirim</span><br><span class="font-semibold text-gray-800">{{ $order->pickup_date }}</span></div>
                    <div><span class="text-gray-500">Waktu Ambil/Pengiriman</span><br><span class="font-semibold text-gray-800">{{ $order->pickup_time }}</span></div>
                </div>
                <div class="space-y-1.5 sm:text-right">
                    <div><span class="text-gray-500">Metode Pengiriman</span><br><span class="font-semibold text-gray-800">{{ $order->delivery_method }}</span></div>
                    <div><span class="text-gray-500">Tujuan Pengiriman</span><br><span class="font-semibold text-gray-800">{{ $order->destination }}</span></div>
                    <div><span class="text-gray-500">Status Pesanan</span><br><span class="font-semibold text-gray-800">{{ ucfirst($order->status) }}</span></div>
                    <div><span class="text-gray-500">Status Pembayaran</span><br><span class="font-semibold text-gray-800">{{ $order->payment_status ?? '-' }}</span></div>
                </div>
            </div>
            <!-- Tabel Produk -->
            <h2 class="text-lg font-bold mb-2 text-pink-600 tracking-tight">Detail Produk</h2>
            <div class="overflow-x-auto rounded-lg border border-gray-100 mb-6">
                <table class="w-full text-xs sm:text-sm bg-white">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700">
                            <th class="py-2 px-2 sm:px-4 font-semibold text-left">Nama Produk</th>
                            <th class="py-2 px-2 sm:px-4 font-semibold text-left">Tipe</th>
                            <th class="py-2 px-2 sm:px-4 font-semibold text-right">Harga</th>
                            <th class="py-2 px-2 sm:px-4 font-semibold text-right">Satuan</th>
                            <th class="py-2 px-2 sm:px-4 font-semibold text-right">Jumlah</th>
                            <th class="py-2 px-2 sm:px-4 font-semibold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @php $total = 0; @endphp
                        @foreach($order->items as $item)
                        @php $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0); $total += $subtotal; @endphp
                        <tr>
                            <td class="py-2 px-2 sm:px-4 align-top break-words">{{ $item->product_name }}</td>
                            <td class="py-2 px-2 sm:px-4 align-top break-words">{{ $item->price_type ?? '-' }}</td>
                            <td class="py-2 px-2 sm:px-4 text-right align-top">Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 sm:px-4 text-right align-top">{{ $item->unit_equivalent ?? '-' }}</td>
                            <td class="py-2 px-2 sm:px-4 text-right align-top">{{ $item->quantity }}</td>
                            <td class="py-2 px-2 sm:px-4 text-right align-top">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="5" class="text-right px-2 py-2 sm:px-4 sm:py-2 font-semibold">Total</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-2 text-right font-semibold text-green-600">Rp{{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                        @php $total_paid = $order->total_paid ?? ($order->payments ? $order->payments->sum('amount') : 0); @endphp
                        @if($total_paid > 0)
                        <tr>
                            <th colspan="5" class="text-right px-2 py-2 sm:px-4 sm:py-2 font-semibold">Total Sudah Dibayar</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-2 text-right font-semibold text-blue-600">Rp{{ number_format($total_paid, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-right px-2 py-2 sm:px-4 sm:py-2 font-semibold">Sisa Pembayaran</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-2 text-right font-semibold text-red-600">Rp{{ number_format(max($total - $total_paid, 0), 0, ',', '.') }}</th>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
            <!-- Bukti Pembayaran -->
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
            <!-- Footer -->
            <div class="text-center text-gray-600 text-xs sm:text-sm mt-10 border-t pt-4">
                <p class="font-medium">Terima kasih telah memesan di Fellie Florist!</p>
                <p class="mt-1 sm:mt-2">Jika ada pertanyaan, silakan hubungi kontak kami.</p>
                <p class="mt-1 sm:mt-2">+62 821-7792-9879 | @fellieflorist</p>
                <p class="mt-2 sm:mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div>
        </div>
        <div class="fixed bottom-2 right-2 sm:bottom-4 sm:right-4 print:hidden z-50">
            <button onclick="window.print()" class="bg-pink-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow hover:bg-pink-700 text-xs sm:text-base">
                Print Invoice
            </button>
        </div>
    </div>
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
            {{-- <div class="text-center text-gray-600 text-xs sm:text-sm mt-4 sm:mt-8">
                <p>Terima kasih telah memesan di Fellie Florist!</p>
                <p class="mt-1 sm:mt-2">Jika ada pertanyaan, silakan hubungi admin kami.</p>
                <p class="mt-4">
                    @if($order->status === 'pending' && config('public_order.enable_public_order_edit'))
                        <a href="{{ route('public.order.edit', ['public_code' => $order->public_code]) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Edit Pesanan</a>
                    @endif
                </p>
                <p class="mt-2 sm:mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div> --}}
        </div>
    </div>
    <div class="fixed bottom-2 right-2 sm:bottom-4 sm:right-4 print:hidden z-50">
        <button onclick="window.print()" class="bg-pink-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow hover:bg-pink-700 text-xs sm:text-base">
            Print Invoice
        </button>
    </div>
</body>
</html>
