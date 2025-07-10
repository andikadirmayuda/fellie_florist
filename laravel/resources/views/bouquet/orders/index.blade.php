@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-pink-700">Daftar Pesanan Buket</h1>
        <a href="{{ route('bouquet.orders.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Pesanan</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. WA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->wa_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('bouquet.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                        <a href="{{ route('bouquet.orders.edit', $order) }}" class="text-green-600 hover:text-green-900">Ubah</a>
                        <form action="{{ route('bouquet.orders.destroy', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada pesanan ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4 px-6 pb-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
