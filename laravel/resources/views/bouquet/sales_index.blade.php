@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4 text-green-600">Daftar Penjualan Buket</h1>
    <a href="{{ route('bouquet.sales.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Penjualan Buket</a>
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
                @foreach($sales as $sale)
                <tr>
                    <td class="border px-2 py-1">{{ $loop->iteration }}</td>
                    <td class="border px-2 py-1">{{ $sale->customer_name }}</td>
                    <td class="border px-2 py-1">{{ $sale->wa_number }}</td>
                    <td class="border px-2 py-1">{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                    <td class="border px-2 py-1">{{ number_format($sale->total_price,0,',','.') }}</td>
                    <td class="border px-2 py-1">
                        <a href="{{ route('bouquet.sales.show', $sale) }}" class="text-blue-600">Detail</a> |
                        <a href="{{ route('bouquet.sales.edit', $sale) }}" class="text-yellow-600">Edit</a> |
                        <form action="{{ route('bouquet.sales.destroy', $sale) }}" method="POST" class="inline">
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
@endsection
