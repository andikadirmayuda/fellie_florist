<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4 text-pink-600">Daftar Pemesanan Buket</h1>
        <a href="{{ route('bouquet.orders.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded mb-4 inline-block">Buat Pemesanan Baru</a>
        <div class="bg-white rounded-sm shadow-md p-0 overflow-x-auto">
            <table class="min-w-full text-sm text-gray-900">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Nomor Pesanan</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-semibold">{{ $order->order_number ?? ('ORDERBQ-' . $order->created_at->format('dmY') . str_pad($order->id, 3, '0', STR_PAD_LEFT)) }}</td>
                        <td class="px-6 py-3">{{ $order->customer_name }}</td>
                        <td class="px-6 py-3">
                            @php
                                $badge = match($order->status ?? 'pending') {
                                    'completed' => 'bg-green-50 text-green-700 border border-green-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
                                    'processed' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                    default => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                };
                                $statusText = match($order->status ?? 'pending') {
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    'processed' => 'Diproses',
                                    default => 'Pending',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $badge }}">{{ $statusText }}</span>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">Rp {{ number_format($order->total_price,2,',','.') }}</td>
                        <td class="px-6 py-3 whitespace-nowrap">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-6 py-3 whitespace-nowrap flex gap-2 items-center">
                            <a href="{{ route('bouquet.orders.show', $order) }}" class="text-blue-500 hover:text-blue-700 font-medium">Lihat</a>
                            <a href="{{ route('bouquet.orders.edit', $order) }}" class="text-gray-600 hover:text-gray-900 font-medium">Ubah</a>
                            <form action="{{ route('bouquet.orders.destroy', $order) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
