<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Detail Transaksi Penjualan
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemesanan</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">No. Transaksi:</dt>
                            <dd class="font-semibold text-blue-700">{{ $sale->order_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Tanggal:</dt>
                            <dd class="text-gray-900">{{ \Carbon\Carbon::parse($sale->order_time)->format('d-m-Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Metode Pembayaran:</dt>
                            <dd class="text-gray-900">{{ ucfirst($sale->payment_method) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Penjualan</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-100 mb-6">
                <table class="min-w-full bg-white rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Tipe Harga</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Harga</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $i => $item)
                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">
                            <td class="px-4 py-2 text-center text-gray-800">{{ $i+1 }}</td>
                            <td class="px-4 py-2 text-gray-900">{{ $item->product->name }}</td>
                            <td class="px-4 py-2 text-gray-800">{{ ucfirst(str_replace('_', ' ', $item->price_type)) }}</td>
                            <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center text-gray-800">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col md:flex-row md:justify-end gap-2">
                <div class="bg-gray-50 rounded-lg p-4 w-full md:w-1/3 border border-gray-100">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold text-gray-700">Total</span>
                        <span class="font-bold text-lg text-blue-700">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                    </div>
                    @if($sale->payment_method == 'cash')
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">Bayar (Cash)</span>
                        <span class="text-gray-900">Rp {{ number_format($sale->cash_given ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">Kembalian</span>
                        <span class="text-gray-900">Rp {{ number_format($sale->change ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="mt-4 mb-2 text-sm text-blue-700">
                <strong>Link Struk Online:</strong><br>
                <a href="{{ route('sales.public_receipt', $sale->public_code) }}" target="_blank" class="underline text-blue-700 hover:text-blue-900">{{ route('sales.public_receipt', $sale->public_code) }}</a>
                <span class="block text-xs text-gray-400">(Link ini dapat dibagikan ke pelanggan, dapat diakses tanpa login)</span>
            </div>
            <div class="mt-4 mb-2 text-sm text-gray-600">
                <strong>Catatan:</strong> Untuk mengirim struk PDF ke WhatsApp pelanggan, silakan:
                <ol class="list-decimal ml-6 mt-1">
                    <li>Download PDF struk dengan klik tombol <b>Download PDF</b> di bawah.</li>
                    <li>Klik <b>Bagikan via WhatsApp</b> untuk mengirim pesan sapaan otomatis.</li>
                    <li>Setelah pesan terkirim, lampirkan file PDF yang sudah diunduh ke chat WhatsApp pelanggan.</li>
                </ol>
                <span class="text-xs text-gray-400">(Pengiriman file PDF otomatis hanya tersedia untuk WhatsApp Business API)</span>
            </div>
            <div class="mt-8 flex flex-wrap justify-end gap-2">
                <a href="{{ route('sales.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition">Kembali</a>
                <button id="wa-share" type="button" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition">Bagikan via WhatsApp</button>
                <a href="{{ route('sales.download_pdf', $sale->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">Download PDF</a>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('wa-share').onclick = function() {
            const message = `Hallo Fellie People %0A%0A` +
                `Terima kasih telah berbelanja di Fellie Florist ! %0A` +
                `Berikut kami lampirkan struk pembelian Anda dalam format PDF sebagai bukti transaksi.%0A%0A` +
                `Semoga bunga yang Anda pilih membawa kebahagiaan dan keindahan.%0A%0A` +
                `Jangan ragu untuk kembali memesan rangkaian bunga spesial lainnya di lain waktu. Kami selalu siap membantu! %0A%0A` +
                `Salam hangat,%0A- Fellie Florist -%0A%0A` +
                `(Struk terlampir dalam bentuk PDF)`;
            window.open(`https://wa.me/?text=${message}`);
        };
    </script>
</x-app-layout>
