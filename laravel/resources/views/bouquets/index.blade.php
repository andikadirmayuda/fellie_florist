<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-700">Master Buket</h1>
            <a href="{{ route('bouquets.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Tambah Buket</a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                                <tr>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama Buket</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Kategori</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Deskripsi</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Gambar</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 transition-all">
                                @forelse($bouquets as $bouquet)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $bouquet->name }}</td>
                                    <td class="px-4 py-2 border">{{ $bouquet->category->name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $bouquet->description }}</td>
                                    <td class="px-4 py-2 border">
                                        @if($bouquet->image)
                                            <img src="{{ asset('storage/' . $bouquet->image) }}" alt="Gambar Buket" class="h-12 rounded shadow">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border text-sm font-medium space-x-2">
                                        <a href="{{ route('bouquets.show', $bouquet) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                        <a href="{{ route('bouquets.edit', $bouquet) }}" class="text-green-600 hover:text-green-900">Ubah</a>
                                        <form action="{{ route('bouquets.destroy', $bouquet) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus buket ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 border text-center text-gray-500">Tidak ada buket ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 px-4 py-2">
                            {{ $bouquets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
