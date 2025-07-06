<x-app-layout>
    <x-slot name="header">
        Daftar Pesanan Publik
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 border">ID</th>
                                    <th class="px-4 py-2 border">Nama Pelanggan</th>
                                    <th class="px-4 py-2 border">Tanggal Ambil/Kirim</th>
                                    <th class="px-4 py-2 border">Metode Pengiriman</th>
                                    <th class="px-4 py-2 border">Status Pesanan</th>
                                    <th class="px-4 py-2 border">Status Bayar</th>
                                    <th class="px-4 py-2 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $order->id }}</td>
                                    <td class="px-4 py-2 border">{{ $order->customer_name }}</td>
                                    <td class="px-4 py-2 border">{{ $order->pickup_date }}</td>
                                    <td class="px-4 py-2 border">{{ $order->delivery_method }}</td>
                                    <td class="px-4 py-2 border">
                                        @php
                                            $statusMap = [
                                                'pending' => 'Menunggu Diproses',
                                                'processed' => 'Diproses',
                                                'packing' => 'Dikemas',
                                                'shipped' => 'Dikirim',
                                                'completed' => 'Selesai',
                                                'done' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $order->status === 'completed' || $order->status === 'done' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status === 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $order->status === 'packing' ? 'bg-pink-100 text-pink-800' : '' }}
                                            {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $statusMap[$order->status] ?? ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @php
                                            $paymentMap = [
                                                'waiting_confirmation' => 'Menunggu Konfirmasi',
                                                'ready_to_pay' => 'Siap Dibayar',
                                                'waiting_payment' => 'Menunggu Pembayaran',
                                                'waiting_verification' => 'Menunggu Verifikasi',
                                                'dp_paid' => 'DP',
                                                'partial_paid' => 'Sebagian Bayar',
                                                'paid' => 'Lunas',
                                                'rejected' => 'Ditolak',
                                                'cancelled' => 'Dibatalkan',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->payment_status === 'waiting_confirmation' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $order->payment_status === 'ready_to_pay' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->payment_status === 'waiting_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->payment_status === 'waiting_verification' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $order->payment_status === 'dp_paid' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $order->payment_status === 'partial_paid' ? 'bg-pink-100 text-pink-800' : '' }}
                                            {{ $order->payment_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $order->payment_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $paymentMap[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.public-orders.show', $order->id) }}" class="text-blue-600 hover:underline">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
