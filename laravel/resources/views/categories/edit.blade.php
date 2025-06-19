<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">
            {{ __('Edit Kategori') }}
        </h1>
    </x-slot>

    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg mx-auto mt-8">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="code" class="block text-gray-700 font-semibold mb-2">Kode Kategori</label>
                <input id="code" type="text" name="code" value="{{ old('code', $category->code) }}" required autofocus class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Contoh: BP, BA, BQ, D</p>
                @error('code')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Kategori</label>
                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Contoh: Bunga Potong, Bunga Artificial</p>
                @error('name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('categories.index') }}" class="mr-3 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Kategori</button>
            </div>
        </form>
    </div>
</x-app-layout>
