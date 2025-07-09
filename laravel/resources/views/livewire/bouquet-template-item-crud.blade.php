@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white rounded shadow">
    <h2 class="text-lg font-bold mb-4">Master Template Item Bouquet</h2>
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-2">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="mb-4 space-y-2">
        <div>
            <label>Bouquet</label>
            <select wire:model.defer="bouquet_id" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                @foreach($bouquets as $bq)
                    <option value="{{ $bq->id }}">{{ $bq->name }}</option>
                @endforeach
            </select>
            @error('bouquet_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Produk</label>
            <select wire:model.defer="product_id" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                @foreach($products as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                @endforeach
            </select>
            @error('product_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Qty</label>
            <input type="number" wire:model.defer="quantity" class="w-full border p-1 rounded" min="1" />
            @error('quantity') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-pink-500 text-white px-4 py-1 rounded">
                {{ $isEdit ? 'Update' : 'Tambah' }}
            </button>
            @if($isEdit)
                <button type="button" wire:click="resetForm" class="bg-gray-300 px-4 py-1 rounded">Batal</button>
            @endif
        </div>
    </form>
    <table class="w-full text-sm mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-1">#</th>
                <th class="p-1">Bouquet</th>
                <th class="p-1">Produk</th>
                <th class="p-1">Qty</th>
                <th class="p-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td class="p-1">{{ $loop->iteration }}</td>
                <td class="p-1">{{ $item->bouquet->name ?? '-' }}</td>
                <td class="p-1">{{ $item->product->name ?? '-' }}</td>
                <td class="p-1">{{ $item->quantity }}</td>
                <td class="p-1">
                    <button wire:click="edit({{ $item->id }})" class="text-blue-600">Edit</button>
                    <button wire:click="delete({{ $item->id }})" class="text-red-600 ml-2" onclick="return confirm('Hapus item ini?')">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
