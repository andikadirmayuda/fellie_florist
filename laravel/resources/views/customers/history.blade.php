<x-app-layout>
    <x-slot name="header">
        <h2 class="font-sans text-xl text-black font-semibold leading-tight flex items-center gap-2">
            <i class="bi bi-clock-history text-lg mr-2"></i>
            Riwayat Pemesanan: {{ $customer->name }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="w-full px-2 sm:px-4">
            <div class="bg-white shadow-lg rounded-sm border p-6">
                <a href="{{ route('customers.index') }}"
                    class="mb-4 inline-flex items-center gap-2 bg-black text-white rounded-sm px-5 py-2 hover:bg-gray-900 text-sm font-semibold">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Pelanggan
                </a>
                @if($orders->count())
                    <div class="overflow-x-auto rounded-sm">
                        <table class="min-w-full bg-white shadow-lg rounded-sm text-xs sm:text-sm">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                        No. Pesanan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-black uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-black uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap font-sans">{{ $order->order_number }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-sans">
                                            {{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-sans flex items-center gap-2">
                                            <i class="bi bi-info-circle text-gray-700"></i>
                                            {{ ucfirst($order->status) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right font-sans">Rp
                                            {{ number_format($order->total + $order->delivery_fee, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="inline-flex items-center gap-2 bg-black text-white rounded-sm px-5 py-2 hover:bg-gray-900 text-xs font-semibold">
                                                <i class="bi bi-eye"></i>
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 font-sans">Belum ada riwayat pemesanan untuk pelanggan ini.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>