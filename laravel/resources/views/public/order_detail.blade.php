<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
    <div
        class="container mx-auto px-2 sm:px-4 py-4 sm:py-8 flex justify-center items-center min-h-screen text-[13px] sm:text-base">
        <div class="bg-white rounded-2xl shadow-xl p-2 sm:p-10 w-full max-w-5xl mx-auto">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            <div class="text-center mb-8">
                <h1
                    class="text-2xl sm:text-3xl font-extrabold text-pink-600 tracking-tight mb-1 flex items-center justify-center gap-2">
                    <i class="bi bi-flower1 text-pink-400 text-2xl"></i> Fellie Florist
                </h1>
                <p class="text-gray-500 text-xs sm:text-base font-medium">Detail Pemesanan Via - Website</p>
                <div class="mt-2 mb-4">
                    <span class="text-sm text-gray-600">Kode Pesanan:</span>
                    <span class="font-mono font-bold text-lg text-pink-600 bg-pink-50 px-3 py-1 rounded-lg border">{{ $order->public_code }}</span>
                </div>
            </div>
            <!-- Status Badge -->
            <div class="flex flex-col sm:flex-row gap-2 justify-center items-center mb-6">
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-semibold text-white text-xs sm:text-sm shadow bg-pink-600">
                    <i class="bi bi-clipboard-check"></i> {{ ucfirst($order->status) }}
                </span>
                @php
                    $paymentStatusMap = [
                        'waiting_confirmation' => 'Menunggu Konfirmasi Stok',
                        'ready_to_pay' => 'Siap Dibayar',
                        'waiting_payment' => 'Menunggu Pembayaran',
                        'waiting_verification' => 'Menunggu Verifikasi Pembayaran',
                        'paid' => 'Lunas',
                        'rejected' => 'Pembayaran Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ];
                    $paymentBg = match ($order->payment_status) {
                        'paid' => '#16a34a',
                        'ready_to_pay' => '#f59e42',
                        'waiting_confirmation' => '#64748b',
                        'waiting_payment' => '#f59e42',
                        'waiting_verification' => '#f59e42',
                        'rejected' => '#dc2626',
                        'cancelled' => '#6b7280',
                        default => '#64748b',
                    };
                @endphp
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-semibold text-white text-xs sm:text-sm shadow"
                    style="background:{{ $paymentBg }};">
                    <i class="bi bi-cash-coin"></i>
                    {{ $paymentStatusMap[$order->payment_status] ?? ucfirst($order->payment_status) }}
                </span>
            </div>
            <!-- Stepper Status Responsive Split for Mobile -->
            <div class="w-full mb-8">
                @php
                    $steps = [
                        'pending' => 'Pesanan Diterima',
                        'processing' => 'Diproses',
                        'packing' => 'Dikemas',
                        'shipped' => 'Dikirim',
                        'done' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                    $statusMap = [
                        'pending' => 'pending',
                        'processed' => 'processing',
                        'processing' => 'processing',
                        'packing' => 'packing',
                        'shipped' => 'shipped',
                        'done' => 'done',
                        'completed' => 'done',
                        'cancelled' => 'cancelled',
                        'canceled' => 'cancelled',
                    ];
                    $currentStatus = strtolower($order->status);
                    $currentStatus = $statusMap[$currentStatus] ?? $currentStatus;
                    $stepKeys = array_keys($steps);
                    $currentIndex = array_search($currentStatus, $stepKeys);
                @endphp
                <!-- Mobile: 2 rows, Desktop: 1 row -->
                <div class="hidden sm:flex flex-nowrap justify-between items-center w-full gap-2 px-1">
                    @foreach($steps as $key => $label)
                        <div class="flex flex-col items-center min-w-[44px] max-w-[60px] flex-shrink-0">
                            <div
                                class="rounded-full w-8 h-8 flex items-center justify-center mb-1 text-[15px] font-bold {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-400' }}">
                                {{ $loop->iteration }}
                            </div>
                            <div class="text-xs text-center font-medium leading-tight {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600' : 'text-gray-400' }}"
                                style="word-break:break-word;">{{ $label }}</div>
                        </div>
                        @if(!$loop->last)
                            <div
                                class="h-1 w-5 bg-gray-200 mt-4 flex-shrink-0 {{ $currentIndex >= $loop->index ? 'bg-pink-500' : '' }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="flex flex-col gap-1 sm:hidden">
                    <div class="grid grid-cols-3 w-full gap-0 px-1">
                        @foreach(array_slice($stepKeys, 0, 3) as $i => $key)
                            <div class="flex flex-col items-center">
                                <div
                                    class="rounded-full w-6 h-6 flex items-center justify-center mb-0.5 text-[11px] font-bold {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-400' }}">
                                    {{ $i + 1 }}
                                </div>
                                <div class="text-[9px] text-center font-medium leading-tight {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600' : 'text-gray-400' }}"
                                    style="word-break:break-word;">{{ $steps[$key] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-3 w-full gap-0 px-1 mt-1">
                        @foreach(array_slice($stepKeys, 3, 3) as $i => $key)
                            <div class="flex flex-col items-center">
                                <div
                                    class="rounded-full w-6 h-6 flex items-center justify-center mb-0.5 text-[11px] font-bold {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-gray-400' }}">
                                    {{ $i + 4 }}
                                </div>
                                <div class="text-[9px] text-center font-medium leading-tight {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600' : 'text-gray-400' }}"
                                    style="word-break:break-word;">{{ $steps[$key] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Info Pemesanan (3 Columns) -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-center items-stretch gap-2 sm:gap-4 w-full mb-2">
                    <!-- Kiri -->
                    <div
                        class="flex-1 min-w-0 max-w-full bg-gray-50 rounded-xl p-3 sm:p-4 flex flex-col justify-center items-start shadow-sm border border-gray-100 text-xs sm:text-sm mb-2 sm:mb-0">
                        <div class="mb-1 sm:mb-2"><span class="text-gray-500">Nama</span><br><span
                                class="font-bold text-gray-800 break-words">{{ $order->customer_name }}</span></div>
                        <div class="mb-1 sm:mb-2"><span class="text-gray-500">Tanggal Ambil/Kirim</span><br><span
                                class="font-bold text-gray-800 break-words">{{ $order->pickup_date }}</span></div>
                        <div class="mb-1 sm:mb-2"><span class="text-gray-500">Metode Pengiriman</span><br><span
                                class="font-bold text-gray-800 break-words">{{ $order->delivery_method }}</span></div>
                    </div>
                    <!-- Tengah -->
                    <div
                        class="flex-1 min-w-0 max-w-full bg-gray-50 rounded-xl p-3 sm:p-4 flex flex-col justify-center items-start shadow-sm border border-gray-100 text-xs sm:text-sm mb-2 sm:mb-0">
                        <div class="mb-1 sm:mb-2"><span class="text-gray-500">No. WhatsApp</span><br><span
                                class="font-bold text-gray-800 break-words">{{ $order->wa_number }}</span></div>
                        <div class="mb-1 sm:mb-2"><span class="text-gray-500">Waktu Ambil/Pengiriman</span><br><span
                                class="font-bold text-gray-800 break-words">{{ $order->pickup_time }}</span></div>
                        <div class="mb-1 sm:mb-2"><span class="text-gray-500">Tujuan Pengiriman</span><br><span
                                class="font-bold text-gray-800 break-words">{{ $order->destination }}</span></div>
                    </div>
                    <!-- Kanan: Informasi Penting -->
                    <div class="flex-1 min-w-0 max-w-full flex flex-col justify-center items-center">
                        <div
                            class="w-full h-full flex flex-col justify-center items-center border-2 border-gray-300 rounded-xl p-3 sm:p-4 bg-white shadow-sm text-xs sm:text-sm">
                            <div class="text-center font-bold text-red-600 text-xs sm:text-base mb-1 sm:mb-2">Informasi
                                Penting !!!</div>
                            <div class="text-xs sm:text-sm text-gray-700 leading-relaxed text-center break-words">
                                {{ $order->info ?? 'Tidak ada informasi tambahan.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="text-base sm:text-lg font-semibold mb-2 mt-2 flex items-center gap-2"><i
                    class="bi bi-box-seam"></i> Produk Dipesan</h2>
            <div class="overflow-x-auto">
                <table class="w-full mb-6 text-xs sm:text-base table-fixed border rounded-lg overflow-hidden">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="py-1 px-2 w-[28%] whitespace-nowrap font-semibold">Nama</th>
                            <th class="py-1 px-2 w-[14%] whitespace-nowrap font-semibold">Tipe</th>
                            <th class="py-1 px-2 w-[14%] text-right whitespace-nowrap font-semibold">Harga</th>
                            <th class="py-1 px-2 w-[14%] text-right whitespace-nowrap font-semibold">Satuan</th>
                            <th class="py-1 px-2 w-[14%] text-right whitespace-nowrap font-semibold">Jumlah</th>
                            <th class="py-1 px-2 w-[16%] text-right whitespace-nowrap font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @php $total = 0; @endphp
                        @foreach($order->items as $item)
                            @php $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
                            $total += $subtotal; @endphp
                            <tr>
                                <td class="py-1 px-2 break-words whitespace-normal align-top">{{ $item->product_name }}</td>
                                <td class="py-1 px-2 break-words whitespace-normal align-top">{{ $item->price_type ?? '-' }}
                                </td>
                                <td class="py-1 px-2 text-right align-top whitespace-nowrap">
                                    Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                <td class="py-1 px-2 text-right align-top whitespace-nowrap">
                                    {{ $item->unit_equivalent ?? '-' }}</td>
                                <td class="py-1 px-2 text-right align-top whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="py-1 px-2 text-right align-top whitespace-nowrap">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="mt-4">
                        <tr>
                            <th colspan="5" class="text-right px-2 py-1 font-semibold text-[9px] sm:text-sm">Total</th>
                            <th
                                class="pr-4 py-1 text-right font-bold text-green-600 text-[11px] sm:text-base whitespace-nowrap">
                                Rp{{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                        @php
                            $totalPaid = $order->payments ? $order->payments->sum('amount') : 0;
                            $sisa = max($total - $totalPaid, 0);
                        @endphp
                        <tr>
                            <th colspan="5" class="text-right px-2 py-1 font-semibold text-[9px] sm:text-sm">Total Sudah
                                Dibayar</th>
                            <th
                                class="pr-4 py-1 text-right font-bold text-blue-600 text-[11px] sm:text-base whitespace-nowrap">
                                Rp{{ number_format($totalPaid, 0, ',', '.') }}</th>
                        </tr>
                        @if($sisa > 0)
                            <tr>
                                <th colspan="5" class="text-right px-2 py-1 font-semibold text-[9px] sm:text-sm">Sisa
                                    Pembayaran</th>
                                <th
                                    class="pr-4 py-1 text-right font-bold text-red-600 text-[11px] sm:text-base whitespace-nowrap">
                                    Rp{{ number_format($sisa, 0, ',', '.') }}</th>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
            @if(!empty($order->payment_proof))
                <div class="my-8 text-center">
                    <h3 class="font-semibold text-base mb-2 flex items-center gap-2 justify-center"><i
                            class="bi bi-receipt"></i> Bukti Pembayaran</h3>
                    @php
                        $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                            class="mx-auto rounded shadow max-h-64 border mb-2" style="max-width:300px;"
                            onerror="this.style.display='none'; document.getElementById('payment-proof-error').style.display='block';" />
                    @elseif(strtolower($ext) == 'pdf')
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                            class="text-blue-600 underline">Lihat Bukti Pembayaran (PDF)</a>
                    @else
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                            class="text-blue-600 underline">Download Bukti Pembayaran</a>
                    @endif
                    <div id="payment-proof-error" style="display:none; color:red;">Bukti pembayaran tidak ditemukan di
                        server.</div>
                </div>
            @endif

            @if($order->payments && $order->payments->count())
                <div class="my-8">
                    <h3 class="font-semibold text-base mb-2 flex items-center gap-2 justify-center"><i
                            class="bi bi-clock-history"></i> Riwayat Pembayaran</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs sm:text-sm table-fixed border rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th class="py-2 px-2 sm:px-4 font-semibold">Tanggal</th>
                                    <th class="py-2 px-2 sm:px-4 font-semibold text-right">Jumlah</th>
                                    <th class="py-2 px-2 sm:px-4 font-semibold">Catatan</th>
                                    <th class="py-2 px-2 sm:px-4 font-semibold">Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($order->payments as $payment)
                                    <tr>
                                        <td class="py-2 px-2 sm:px-4 align-top">
                                            {{ $payment->created_at ? $payment->created_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td class="py-2 px-2 sm:px-4 text-right align-top">
                                            Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td class="py-2 px-2 sm:px-4 align-top">{{ $payment->note ?? '-' }}</td>
                                        <td class="py-2 px-2 sm:px-4 align-top">
                                            @if($payment->proof)
                                                @php $ext = pathinfo($payment->proof, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank"><img
                                                            src="{{ asset('storage/' . $payment->proof) }}" alt="Bukti"
                                                            class="inline-block rounded shadow max-h-12 border"
                                                            style="max-width:60px;" /></a>
                                                @elseif(strtolower($ext) == 'pdf')
                                                    <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank"
                                                        class="text-blue-600 underline">PDF</a>
                                                @else
                                                    <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank"
                                                        class="text-blue-600 underline">File</a>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @if(!empty($order->packing_photo))
                <div class="my-8 text-center">
                    <h3 class="font-semibold text-base mb-2 flex items-center gap-2 justify-center">
                        <i class="bi bi-camera text-pink-500"></i> 
                        <i class="bi bi-box-seam text-blue-500"></i>
                        Foto Barang Saat Dikemas
                    </h3>
                    <img src="{{ asset('storage/' . $order->packing_photo) }}" alt="Foto Barang Dikemas"
                        class="mx-auto rounded shadow max-h-64 border" onerror="this.style.display='none';" />
                </div>
            @endif
            <div class="text-center text-gray-500 text-xs sm:text-sm mt-8">
                <p class="font-medium">Terima kasih telah memesan di Fellie Florist!</p>
                <p class="mt-1 sm:mt-2">Jika ada pertanyaan, silakan hubungi admin kami.</p>
                <p class="mt-2 sm:mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
    <div class="w-full flex justify-center mt-4 mb-4">
        <a href="{{ route('public.flowers') }}"
            class="inline-flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white font-bold px-4 py-2 rounded-lg shadow transition">
            <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar Bunga
        </a>
    </div>
</body>

</html>