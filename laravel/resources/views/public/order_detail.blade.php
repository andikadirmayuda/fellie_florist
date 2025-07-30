<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Notification Styles -->
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
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
                                class="font-bold text-gray-800 break-words">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d-m-Y') }}</span></div>
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
                                {{ $order->info ?? 'Harap Dibaca seluruh informasinya, Jika ada pertanyaan silahkan hubungi kami' }}
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

            <!-- Debug Info (remove in production) -->
            {{-- @if($sisa > 0)
                <div class="my-4 p-2 bg-gray-100 rounded text-xs">
                    <strong>Debug Info:</strong> Sisa: Rp{{ number_format($sisa, 0, ',', '.') }}, 
                    Payment Status: {{ $order->payment_status }}, 
                    Show Payment: {{ in_array($order->payment_status, ['waiting_confirmation', 'ready_to_pay', 'waiting_payment', 'waiting_verification']) ? 'Yes' : 'No' }}
                </div>
            @endif --}}

            <!-- Informasi Pembayaran Section -->
            @if($sisa > 0)
                <div "my-8">
                    <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border-2 border-orange-200 rounded-2xl p-4 sm:p-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="bg-orange-500 rounded-full p-2 mr-3">
                                <i class="bi bi-credit-card-2-front text-white text-lg"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-orange-800">Informasi Pembayaran</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Transfer Bank BCA -->
                            <div class="bg-white rounded-xl border-2 border-blue-200 p-4 shadow-sm">
                                <div class="flex items-center mb-3">
                                    <div class="bg-blue-600 rounded-lg p-2 mr-3">
                                        <i class="bi bi-bank text-white"></i>
                                    </div>
                                    <h4 class="font-bold text-blue-800 text-sm sm:text-base">Transfer Bank BCA</h4>
                                </div>
                                
                                <div class="space-y-2 text-xs sm:text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">No. Rekening:</span>
                                        <span class="font-bold text-blue-800">0213341089</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Atas Nama:</span>
                                        <span class="font-bold text-blue-800">Tia Hanifah</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Bank:</span>
                                        <span class="font-bold text-blue-800">BCA (Bank Central Asia)</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="text-center">
                                        <span class="text-xs text-gray-600">Jumlah Transfer:</span>
                                        <div class="text-lg sm:text-xl font-bold text-green-600">
                                            Rp{{ number_format($sisa, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <button onclick="copyToClipboard('0213341089')" 
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-lg transition duration-200">
                                        <i class="bi bi-clipboard"></i> Salin No. Rekening
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Petunjuk Pembayaran -->
                            <div class="bg-white rounded-xl border-2 border-green-200 p-4 shadow-sm">
                                <div class="flex items-center mb-3">
                                    <div class="bg-green-600 rounded-lg p-2 mr-3">
                                        <i class="bi bi-list-check text-white"></i>
                                    </div>
                                    <h4 class="font-bold text-green-800 text-sm sm:text-base">Petunjuk Pembayaran:</h4>
                                </div>
                                
                                <ol class="text-xs sm:text-sm space-y-2 text-gray-700">
                                    <li class="flex items-start">
                                        <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">1</span>
                                        <span>Transfer sesuai jumlah yang bertanda</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">2</span>
                                        <span>Foto bukti transfer</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">3</span>
                                        <span>Kirim bukti transfer via WhatsApp</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">4</span>
                                        <span>Tunggu konfirmasi dari admin</span>
                                    </li>
                                </ol>
                                
                                <div class="mt-4 text-center">
                                    @php
                                        $waMessage = "🌸 *Halo, Fellie Florist*\n";
                                        $waMessage .= "══════════\n\n";
                                        $waMessage .= "Saya ingin mengirim bukti pembayaran untuk:\n\n";
                                        $waMessage .= "📋 *Pesanan :* {$order->public_code}\n";
                                        $waMessage .= "🔗 *Link :* " . url("/order/{$order->public_code}") . "\n\n";
                                        $waMessage .= "👤 *Nama :* {$order->customer_name}\n";
                                        $waMessage .= "📱 *WhatsApp :* {$order->wa_number}\n";
                                        $waMessage .= "📅 *Tanggal :* " . \Carbon\Carbon::parse($order->pickup_date)->format('d-m-Y') . "\n";
                                        $waMessage .= "⏰ *Waktu :* {$order->pickup_time}\n";
                                        $waMessage .= "🚚 *Pengiriman :* {$order->delivery_method}\n";
                                        $waMessage .= "📍 *Tujuan :* {$order->destination}\n\n";
                                        $waMessage .= "💰 *Total Pesanan :* Rp " . number_format($total, 0, ',', '.') . "\n\n";
                                        $waMessage .= "═══════════\n";
                                        $waMessage .= "Mohon konfirmasi pembayaran 🙏\n";
                                        $waMessage .= "Terima kasih 😊";
                                        $encodedMessage = urlencode($waMessage);
                                    @endphp
                                    <a href="https://wa.me/6282177929879?text={{ $encodedMessage }}" 
                                       target="_blank"
                                       class="bg-green-500 hover:bg-green-600 text-white text-xs px-4 py-2 rounded-lg transition duration-200 inline-flex items-center gap-2 shadow-md hover:shadow-lg">
                                        <i class="bi bi-whatsapp text-lg"></i> 
                                        <span class="font-medium">Kirim Bukti Transfer</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
    
    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess();
                }, function(err) {
                    fallbackCopyTextToClipboard(text);
                });
            } else {
                fallbackCopyTextToClipboard(text);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess();
                }
            } catch (err) {
                console.error('Fallback: Could not copy text: ', err);
            }
            document.body.removeChild(textArea);
        }

        function showCopySuccess() {
            // Use global toast notification system if available
            if (typeof showToast === 'function') {
                showToast('Nomor rekening berhasil disalin!', 'success');
            } else {
                // Fallback to simple alert
                alert('Nomor rekening berhasil disalin!');
            }
        }
    </script>
    
    <!-- Include cart.js for toast notifications -->
    <script src="{{ asset('js/cart.js') }}"></script>
</body>

</html>