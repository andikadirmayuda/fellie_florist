<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-pink-700">Tambah Kategori Buket</h1>
    </x-slot>
    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('bouquet-categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kode Kategori</label>
                            <input type="text" name="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required maxlength="10" value="{{ old('code') }}">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('name') }}">
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('bouquet-categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
