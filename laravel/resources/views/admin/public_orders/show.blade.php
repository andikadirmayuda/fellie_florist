<x-app-layout>
    <x-slot name="header">
        Detail Pesanan Publik
    </x-slot>
    @php
        $steps = [
            'pending' => 'Pesanan Diterima',
            'processed' => 'Diproses',
            'packing' => 'Dikemas',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        $statusMap = [
            'pending' => 'pending',
            'processed' => 'processed',
            'processing' => 'processed', // alias lama ke baru
            'packing' => 'packing',
            'shipped' => 'shipped',
            'completed' => 'completed',
            'done' => 'completed', // alias lama ke baru
            'cancelled' => 'cancelled',
            'canceled' => 'cancelled',
        ];
        $currentStatus = strtolower($order->status);
        $currentStatus = $statusMap[$currentStatus] ?? $currentStatus;
        $stepKeys = array_keys($steps);
        $currentIndex = array_search($currentStatus, $stepKeys);
    @endphp
    <div class="container mx-auto py-8">
        <div class="mb-4">
            <a href="{{ route('admin.public-orders.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke daftar pesanan</a>
        </div>
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-2">Informasi Pelanggan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1 mb-4">
                <div>Nama: <b>{{ $order->customer_name }}</b></div>
                <div>No. WhatsApp: <b>{{ $order->wa_number }}</b></div>
                <div>Tanggal Ambil/Kirim: <b>{{ $order->pickup_date }}</b></div>
                <div>Waktu Ambil/Pengiriman: <b>{{ $order->pickup_time }}</b></div>
                <div>Metode Pengiriman: <b>{{ $order->delivery_method }}</b></div>
                <div>Tujuan Pengiriman: <b>{{ $order->destination }}</b></div>
            </div>
            <div class="mb-4">
                <h3 class="font-semibold mb-1">Status Pesanan</h3>
                <div class="flex w-full justify-between mb-2">
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
                <div class="text-xs sm:text-base mt-2">Status: <b>{{ ucfirst($order->status) }}</b></div>
                @if(!in_array($order->status, ['completed','done','cancelled','canceled']))
                <form method="POST" action="{{ route('admin.public-orders.update-status', $order->id) }}" class="inline-flex items-center gap-2 mt-2" enctype="multipart/form-data" id="statusForm">
                    @csrf
                    <select name="status" class="border rounded p-1 mx-2" id="statusSelect">
                        @foreach($steps as $key => $label)
                            <option value="{{ $key }}" @if($currentStatus == $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="file" name="packing_photo" id="packingPhotoInput" accept="image/*" class="hidden" />
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Update Status</button>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const statusSelect = document.getElementById('statusSelect');
                    const packingPhotoInput = document.getElementById('packingPhotoInput');
                    function togglePackingPhoto() {
                        if (statusSelect.value === 'packing') {
                            packingPhotoInput.classList.remove('hidden');
                            packingPhotoInput.required = true;
                        } else {
                            packingPhotoInput.classList.add('hidden');
                            packingPhotoInput.required = false;
                        }
                    }
                    statusSelect.addEventListener('change', togglePackingPhoto);
                    togglePackingPhoto();
                });
                </script>
                @endif
            </div>
        </div>
        <div class="bg-white rounded shadow p-6 mb-6">
            <h3 class="font-semibold mb-2">Status Pembayaran</h3>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                @php
                    $paymentStatusMap = [
                        'waiting_confirmation' => 'Menunggu Konfirmasi Stok',
                        'ready_to_pay' => 'Siap Dibayar',
                        'waiting_payment' => 'Menunggu Pembayaran',
                        'waiting_verification' => 'Menunggu Verifikasi Pembayaran',
                        'dp_paid' => 'DP (Uang Muka)',
                        'partial_paid' => 'Sebagian Terbayar',
                        'paid' => 'Lunas',
                        'rejected' => 'Pembayaran Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ];
                    $paymentBg = match($order->payment_status) {
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
                <span class="inline-block px-2 py-1 rounded text-white font-semibold text-sm" style="background: {{ $paymentBg }}; min-width:150px; text-align:center;">
                    {{ $paymentStatusMap[$order->payment_status] ?? ucfirst($order->payment_status) }}
                </span>
                @if(!in_array($order->payment_status, ['paid','rejected','cancelled']) && !in_array($order->status, ['cancelled','completed','done']))
                <form method="POST" action="{{ route('admin.public-orders.update-payment-status', $order->id) }}" class="inline-flex items-center gap-2 ml-2" enctype="multipart/form-data" id="paymentStatusForm">
                    @csrf
                    <select name="payment_status" class="border rounded p-1 mx-2" id="paymentStatusSelect">
                        @foreach($paymentStatusMap as $key => $label)
                            <option value="{{ $key }}" @if($order->payment_status == $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="file" name="payment_proof" id="paymentProofInput" accept="image/*,application/pdf" class="border rounded p-1" style="max-width:180px; display:none;">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Update Status Pembayaran</button>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const paymentStatusSelect = document.getElementById('paymentStatusSelect');
                    const paymentProofInput = document.getElementById('paymentProofInput');
                    const paymentStatusForm = document.getElementById('paymentStatusForm');
                    function togglePaymentProof() {
                        const val = paymentStatusSelect.value;
                        if (["paid", "dp_paid", "partial_paid"].includes(val)) {
                            paymentProofInput.style.display = '';
                            paymentProofInput.required = true;
                        } else {
                            paymentProofInput.style.display = 'none';
                            paymentProofInput.required = false;
                        }
                    }
                    paymentStatusSelect.addEventListener('change', togglePaymentProof);
                    togglePaymentProof();
                    paymentStatusForm.addEventListener('submit', function(e) {
                        if (["paid", "dp_paid", "partial_paid"].includes(paymentStatusSelect.value) && !paymentProofInput.value) {
                            alert('Silakan pilih file bukti pembayaran sebelum submit!');
                            paymentProofInput.focus();
                            e.preventDefault();
                        }
                    });
                });
                </script>
                @endif
            </div>
        </div>
        <div class="bg-white rounded shadow p-6 mb-6">
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

        @if(!empty($order->payment_proof))
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="font-semibold text-base mb-1">Bukti Pembayaran:</h3>
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

        @if(!empty($order->packing_photo))
        <div class="bg-white rounded shadow p-6 mb-6">
            <h3 class="font-semibold text-base mb-1">Foto Barang Saat Dikemas:</h3>
            <img src="{{ asset('storage/' . $order->packing_photo) }}"
                 alt="Foto Barang Dikemas"
                 class="mx-auto rounded shadow max-h-64 border"
                 onerror="this.style.display='none'; document.getElementById('photo-error').style.display='block';" />
            <div id="photo-error" style="display:none; color:red;">Foto tidak ditemukan di server.</div>
        </div>
        @endif
        <div class="bg-white rounded shadow p-6 mb-6 text-center">
            @if(!empty($order->public_code))
                <a href="{{ route('public.order.invoice', ['public_code' => $order->public_code]) }}" target="_blank" class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded mb-2">
                    <i class="bi bi-receipt mr-1"></i>Lihat Invoice Publik
                </a>
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->wa_number)) }}?text={{ urlencode('Terima kasih telah memesan di Fellie Florist! Berikut link invoice pesanan Anda: ' . route('public.order.invoice', ['public_code' => $order->public_code])) }}" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="bi bi-whatsapp mr-1"></i>Kirim Invoice ke WhatsApp
                </a>
                @if(config('public_order.enable_public_order_edit') && $order->status === 'pending')
                    <a href="{{ route('public.order.edit', ['public_code' => $order->public_code]) }}" target="_blank" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mb-2">
                        <i class="bi bi-pencil mr-1"></i>Edit Pesanan (Publik)
                    </a>
                @endif
            @else
                <div class="text-red-600 font-semibold">Kode invoice publik belum tersedia. Silakan edit/migrasi data order ini.</div>
            @endif
        </div>
    </div>
</x-app-layout>
