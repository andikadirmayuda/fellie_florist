
<x-app-layout>
    <x-slot name="title">🌸 Daftar Bunga Ready Stock</x-slot>
    <div class="min-h-screen bg-white text-black flex flex-col items-center py-8 px-2">
        <div class="mt-4 text-sm text-gray-600 text-center">
            Terakhir diperbarui: {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') : '-' }}
        </div>
        <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center">
            🌸 Daftar Bunga Ready Stock
        </h1>
        <div class="w-full max-w-2xl overflow-x-auto">
            <table class="min-w-full border border-black rounded-lg overflow-hidden">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="py-2 px-3 text-left">No</th>
                        <th class="py-2 px-3 text-left">Gambar</th>
                        <th class="py-2 px-3 text-left">Nama Bunga</th>
                        <th class="py-2 px-3 text-left">Kategori</th>
                        <th class="py-2 px-3 text-left">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flowers as $flower)
                    <tr class="border-b border-black hover:bg-gray-100">
                        <td class="py-2 px-3">{{ $loop->iteration }}</td>
                        <td class="py-2 px-3">
                            @if($flower->image)
                                <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}" class="h-12 w-12 object-cover rounded">
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="py-2 px-3">{{ $flower->name }}</td>
                        <td class="py-2 px-3">{{ $flower->category->name ?? '-' }}</td>
                        <td class="py-2 px-3">{{ $flower->current_stock }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada bunga ready stock.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
