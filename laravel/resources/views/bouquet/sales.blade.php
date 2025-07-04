@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4 text-green-600">Penjualan Buket</h1>
    <div class="bg-white rounded shadow p-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('bouquet.sales.store') }}">
            @csrf
            <div id="flowers-list">
                <div class="flex gap-2 mb-2 flower-row">
                    <select name="flowers[0][product_id]" class="border rounded px-2 py-1" required>
                        <option value="">Pilih Bunga</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="flowers[0][quantity]" class="border rounded px-2 py-1 w-24" min="1" placeholder="Jumlah" required>
                    <button type="button" class="add-flower bg-blue-500 text-white px-2 rounded">+</button>
                </div>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded mt-2">Jual Buket</button>
        </form>
    </div>
</div>
<script>
    let flowerIndex = 1;
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.add-flower').addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'flex gap-2 mb-2 flower-row';
            row.innerHTML = `
                <select name="flowers[${flowerIndex}][product_id]" class="border rounded px-2 py-1" required>
                    <option value="">Pilih Bunga</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                    @endforeach
                </select>
                <input type="number" name="flowers[${flowerIndex}][quantity]" class="border rounded px-2 py-1 w-24" min="1" placeholder="Jumlah" required>
                <button type="button" class="remove-flower bg-red-500 text-white px-2 rounded">-</button>
            `;
            document.getElementById('flowers-list').appendChild(row);
            row.querySelector('.remove-flower').addEventListener('click', function() {
                row.remove();
            });
            flowerIndex++;
        });
    });
</script>
@endsection
