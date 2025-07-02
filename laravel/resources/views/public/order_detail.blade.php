<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8 flex justify-center items-center min-h-screen text-[13px] sm:text-base">
        <div class="bg-white rounded-lg shadow-lg p-2 sm:p-6 w-full max-w-[95vw] sm:max-w-2xl mx-auto">
            <div class="text-center mb-4 sm:mb-8">
                <h1 class="text-xl sm:text-3xl font-bold text-pink-600">Fellie Florist</h1>
                <p class="text-gray-600 text-xs sm:text-base">Detail Pemesanan Publik</p>
            </div>
            <!-- Stepper Status -->
            <div class="flex justify-between items-center mb-6">
                @php
                    $steps = [
                        'pending' => 'Pesanan Diterima',
                        'processing' => 'Diproses',
                        'packing' => 'Dikemas',
                        'shipped' => 'Dikirim',
                        'done' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                    // Mapping status dari database ke key steps
                    $statusMap = [
                        'pending' => 'pending',
                        'processing' => 'processing',
                        'packing' => 'packing',
                        'shipped' => 'shipped',
                        'done' => 'done',
                        'completed' => 'done', // mapping Completed ke done
                        'cancelled' => 'cancelled',
                        'canceled' => 'cancelled',
                    ];
                    $currentStatus = strtolower($order->status);
                    $currentStatus = $statusMap[$currentStatus] ?? $currentStatus;
                    $stepKeys = array_keys($steps);
                    $currentIndex = array_search($currentStatus, $stepKeys);
                @endphp
                <div class="flex w-full justify-between">
                    @foreach($steps as $key => $label)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="rounded-full w-8 h-8 flex items-center justify-center mb-1 {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'bg-pink-600 text-white' : 'bg-gray-300 text-gray-500' }}">
                                {{ $loop->iteration }}
                            </div>
                            <div class="text-xs text-center {{ $currentStatus === $key || $currentIndex > array_search($key, $stepKeys) ? 'text-pink-600 font-bold' : 'text-gray-500' }}">{{ $label }}</div>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-1 bg-gray-300 mx-1 sm:mx-2 mt-3 {{ $currentIndex >= $loop->index ? 'bg-pink-600' : '' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- Info Pemesanan -->
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
            <div class="text-center text-gray-600 text-xs sm:text-sm mt-4 sm:mt-8">
                <p>Terima kasih telah memesan di Fellie Florist!</p>
                <p class="mt-1 sm:mt-2">Jika ada pertanyaan, silakan hubungi admin kami.</p>
                <p class="mt-2 sm:mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
