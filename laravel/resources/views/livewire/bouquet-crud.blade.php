@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white rounded shadow">
    <h2 class="text-lg font-bold mb-4">Master Produk Bouquet</h2>
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-2">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="mb-4 space-y-2">
        <div>
            <label>Nama Produk Bouquet</label>
            <input type="text" wire:model.defer="name" class="w-full border p-1 rounded" />
            @error('name') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Kategori</label>
            <select wire:model.defer="category_id" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Deskripsi</label>
            <textarea wire:model.defer="description" class="w-full border p-1 rounded"></textarea>
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
                <th class="p-1">Nama</th>
                <th class="p-1">Kategori</th>
                <th class="p-1">Deskripsi</th>
                <th class="p-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bouquets as $bq)
            <tr>
                <td class="p-1">{{ $loop->iteration }}</td>
                <td class="p-1">{{ $bq->name }}</td>
                <td class="p-1">{{ $bq->category->name ?? '-' }}</td>
                <td class="p-1">{{ $bq->description }}</td>
                <td class="p-1">
                    <button wire:click="edit({{ $bq->id }})" class="text-blue-600">Edit</button>
                    <button wire:click="delete({{ $bq->id }})" class="text-red-600 ml-2" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
