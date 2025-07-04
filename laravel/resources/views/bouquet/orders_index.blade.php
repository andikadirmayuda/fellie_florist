<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4 text-pink-600">Daftar Pemesanan Buket</h1>
        <a href="{{ route('bouquet.orders.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Pemesanan Buket</a>
        <div class="bg-white rounded shadow p-6">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">#</th>
                        <th class="border px-2 py-1">Nama</th>
                        <th class="border px-2 py-1">WA</th>
                        <th class="border px-2 py-1">Tanggal</th>
                        <th class="border px-2 py-1">Total</th>
                        <th class="border px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="border px-2 py-1">{{ $loop->iteration }}</td>
                        <td class="border px-2 py-1">{{ $order->customer_name }}</td>
                        <td class="border px-2 py-1">{{ $order->wa_number }}</td>
                        <td class="border px-2 py-1">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border px-2 py-1">{{ number_format($order->total_price,0,',','.') }}</td>
                        <td class="border px-2 py-1">
                            <a href="{{ route('bouquet.orders.show', $order) }}" class="text-blue-600">Detail</a> |
                            <a href="{{ route('bouquet.orders.edit', $order) }}" class="text-yellow-600">Edit</a> |
                            <form action="{{ route('bouquet.orders.destroy', $order) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
