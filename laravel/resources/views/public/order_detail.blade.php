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
                @php
                    $statusIndo = [
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'processed' => 'Diproses',
                        'packing' => 'Sedang Dikemas',
                        'ready' => 'Sudah Siap',
                        'shipping' => 'Dikirim',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Terkirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ];
                @endphp
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-semibold text-white text-xs sm:text-sm shadow bg-pink-600">
                    <i class="bi bi-clipboard-check"></i> {{ $statusIndo[$order->status] ?? ucfirst($order->status) }}
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
                    // Base steps untuk flow normal pesanan
                    $baseSteps = [
                        'pending' => 'Pesanan Diterima',
                        'processing' => 'Diproses',
                        'packing' => 'Dikemas',
                        'ready' => 'Sudah Siap',
                        'shipped' => 'Dikirim',
                        'done' => 'Selesai',
                    ];
                    
                    // Tambahkan status dibatalkan hanya jika pesanan dibatalkan
                    $steps = $baseSteps;
                    if (in_array(strtolower($order->status), ['cancelled', 'canceled'])) {
                        $steps['cancelled'] = 'Dibatalkan';
                    }
                    
                    $statusMap = [
                        'pending' => 'pending',
                        'processed' => 'processing',
                        'processing' => 'processing',
                        'packing' => 'packing',
                        'ready' => 'ready',
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
                @php 
                    $isCancelled = in_array(strtolower($order->status), ['cancelled', 'canceled']);
                @endphp
                
                @if($isCancelled)
                    <!-- Special layout untuk pesanan dibatalkan di desktop -->
                    <div class="hidden sm:flex justify-center">
                        <div class="inline-flex items-center px-6 py-3 bg-red-100 border border-red-300 rounded-xl">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-x text-white text-lg"></i>
                            </div>
                            <span class="text-red-700 font-semibold text-lg">Pesanan Dibatalkan</span>
                        </div>
                    </div>
                @else
                    <!-- Layout normal untuk desktop -->
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
                @endif
                <div class="flex flex-col gap-1 sm:hidden">
                    @php 
                        $stepCount = count($steps);
                        $isCancelled = in_array(strtolower($order->status), ['cancelled', 'canceled']);
                    @endphp
                    
                    @if($isCancelled)
                        <!-- Special layout untuk pesanan dibatalkan -->
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-red-100 border border-red-300 rounded-lg">
                                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center mr-2">
                                    <i class="bi bi-x text-white text-sm"></i>
                                </div>
                                <span class="text-red-700 font-semibold text-sm">Pesanan Dibatalkan</span>
                            </div>
                        </div>
                    @else
                        <!-- Layout normal untuk 6 steps (3 + 3) -->
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
                    @endif
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
            
            <!-- Catatan Pesanan -->
            @if(!empty($order->notes))
                <div class="mb-4 sm:mb-6">
                    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 border border-blue-200 rounded-lg sm:rounded-xl shadow-sm overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-3 sm:px-6 py-2 sm:py-3">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-white bg-opacity-20 rounded-full mr-2 sm:mr-3">
                                    <i class="bi bi-chat-left-text text-white text-xs sm:text-sm"></i>
                                </div>
                                <h3 class="font-bold text-white text-sm sm:text-base lg:text-lg">Catatan Pesanan</h3>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="p-3 sm:p-4 lg:p-6">
                            <div class="bg-white rounded-lg p-3 sm:p-4 lg:p-5 border border-gray-100 shadow-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-2 sm:mr-3 hidden sm:block">
                                        <i class="bi bi-quote text-blue-400 text-lg sm:text-xl lg:text-2xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-gray-800 text-xs sm:text-sm lg:text-base leading-relaxed whitespace-pre-wrap break-words break-all font-medium italic">
                                            {{ $order->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
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
                            // Gunakan amount_paid langsung dari order (yang di-set admin)
                            $totalPaid = $order->amount_paid ?? 0;
                            // Jika status pembayaran sudah lunas, sisa pembayaran harus 0
                            $sisa = $order->payment_status === 'paid' ? 0 : max($total - $totalPaid, 0);
                            
                            // Untuk tampilan "Total Sudah Dibayar", jika status lunas maka tampilkan total penuh
                            $displayTotalPaid = $order->payment_status === 'paid' ? $total : $totalPaid;
                        @endphp
                        <tr>
                            <th colspan="5" class="text-right px-2 py-1 font-semibold text-[9px] sm:text-sm">Total Sudah
                                Dibayar</th>
                            <th
                                class="pr-4 py-1 text-right font-bold text-blue-600 text-[11px] sm:text-base whitespace-nowrap">
                                Rp{{ number_format($displayTotalPaid, 0, ',', '.') }}</th>
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
            @if($sisa > 0 && $order->payment_status !== 'paid')
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
            
            <!-- Pesan untuk pesanan yang sudah lunas -->
            @if($order->payment_status === 'paid' && $sisa == 0)
                <div class="my-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-4 sm:p-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="bg-green-500 rounded-full p-3 mr-3">
                                <i class="bi bi-check-circle text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-green-800">Pembayaran Lunas!</h3>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-green-700 text-sm sm:text-base mb-3">
                                Terima kasih! Pembayaran pesanan Anda telah diterima dengan lengkap.
                            </p>
                            <p class="text-green-600 text-xs sm:text-sm">
                                Pesanan Anda sedang diproses. Silakan pantau status pesanan di halaman ini.
                            </p>
                        </div>
                        
                        @if($order->status === 'pending')
                            <div class="mt-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="bi bi-clock mr-1"></i>
                                    Menunggu diproses oleh admin
                                </span>
                            </div>
                        @endif
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

            @if(!empty($order->packing_photo) || !empty($order->packing_files))
                <div class="my-8 text-center">
                    <h3 class="font-semibold text-base mb-4 flex items-center gap-2 justify-center">
                        <i class="bi bi-camera text-pink-500"></i> 
                        <i class="bi bi-box-seam text-blue-500"></i>
                        Foto & Video Packing
                    </h3>
                    
                    @php
                        $packingFiles = [];
                        
                        // Prioritize new multiple files format
                        if (!empty($order->packing_files)) {
                            $files = is_string($order->packing_files) ? json_decode($order->packing_files, true) : $order->packing_files;
                            if (is_array($files)) {
                                $packingFiles = $files;
                            }
                        } 
                        // Fallback to old single photo format only if no packing_files
                        elseif (!empty($order->packing_photo)) {
                            $packingFiles[] = $order->packing_photo;
                        }
                    @endphp
                    
                    @if(count($packingFiles) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
                            @foreach($packingFiles as $index => $file)
                                @php
                                    $filePath = asset('storage/' . $file);
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm']);
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                @endphp
                                
                                <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                                    @if($isVideo)
                                        <video controls class="w-full h-48 rounded-lg object-cover bg-black mb-2"
                                               onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <source src="{{ $filePath }}" type="video/{{ $ext }}">
                                            Browser Anda tidak mendukung video.
                                        </video>
                                        <div style="display:none;" class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Video tidak ditemukan.
                                        </div>
                                        <div class="flex items-center justify-center text-sm text-gray-600">
                                            <i class="bi bi-play-circle mr-2 text-blue-500"></i>
                                            Video Packing {{ $index + 1 }}
                                        </div>
                                    @elseif($isImage)
                                        <img src="{{ $filePath }}" alt="Foto Packing {{ $index + 1 }}"
                                             class="w-full h-48 object-cover rounded-lg border border-gray-200 mb-2 cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="openImageModal('{{ $filePath }}', 'Foto Packing {{ $index + 1 }}')"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                                        <div style="display:none;" class="text-center p-4 text-red-600 bg-red-50 rounded-lg border border-red-200">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Foto tidak ditemukan.
                                        </div>
                                        <div class="flex items-center justify-center text-sm text-gray-600">
                                            <i class="bi bi-camera mr-2 text-green-500"></i>
                                            Foto Packing {{ $index + 1 }}
                                        </div>
                                    @else
                                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-2">
                                            <div class="text-center">
                                                <i class="bi bi-file-earmark text-3xl text-gray-400 mb-2"></i>
                                                <p class="text-sm text-gray-500">File {{ $index + 1 }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $filePath }}" target="_blank" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <i class="bi bi-download mr-1"></i>
                                            Download File
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-8 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="bi bi-camera text-4xl mb-3"></i>
                            <p>Belum ada foto atau video packing.</p>
                        </div>
                    @endif
                </div>
                
                <!-- Image Modal for Order Detail -->
                <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
                    <div class="relative max-w-4xl max-h-full">
                        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                        <button onclick="closeImageModal()" 
                                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div id="modalTitle" class="absolute bottom-4 left-4 text-white bg-black bg-opacity-50 px-3 py-1 rounded-lg text-sm"></div>
                    </div>
                </div>
                
                <script>
                    function openImageModal(src, title) {
                        document.getElementById('modalImage').src = src;
                        document.getElementById('modalTitle').textContent = title;
                        document.getElementById('imageModal').classList.remove('hidden');
                    }
                    
                    function closeImageModal() {
                        document.getElementById('imageModal').classList.add('hidden');
                    }
                    
                    // Close modal when clicking outside
                    document.getElementById('imageModal').addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeImageModal();
                        }
                    });
                    
                    // Close modal with Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            closeImageModal();
                        }
                    });
                </script>
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