
<x-app-layout>
    <x-slot name="header">
        Daftar Pesanan Publik
    </x-slot>
    <div class="container mx-auto py-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Nama Pelanggan</th>
                        <th class="px-4 py-2 border">Tanggal Ambil/Kirim</th>
                        <th class="px-4 py-2 border">Metode</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-2 border">{{ $order->id }}</td>
                        <td class="px-4 py-2 border">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2 border">{{ $order->pickup_date }}</td>
                        <td class="px-4 py-2 border">{{ $order->delivery_method }}</td>
                        <td class="px-4 py-2 border">{{ ucfirst($order->status) }}</td>
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
</x-app-layout>
