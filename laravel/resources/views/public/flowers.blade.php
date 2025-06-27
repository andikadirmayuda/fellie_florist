
<x-app-layout>
    <x-slot name="title">🌸 Daftar Bunga Ready Stock</x-slot>
    <div class="min-h-screen bg-white text-black flex flex-col items-center py-8 px-2">
        <div class="mt-4 text-sm text-gray-600 text-center">
            Terakhir diperbarui: {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') : '-' }}
        </div>
        <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center">
            🌸 Daftar Bunga Ready Stock
        </h1>
        <div class="w-full max-w-6xl mx-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @forelse($flowers as $flower)
                <div class="bg-white border border-black rounded-xl shadow hover:shadow-lg transition group flex flex-col overflow-hidden relative">
                    <div class="relative h-48 sm:h-56 md:h-60 w-full overflow-hidden flex items-center justify-center bg-black">
                        @if($flower->image)
                            <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-400 text-6xl">-</div>
                        @endif
                        <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-60 px-2 py-1">
                            <span class="text-white font-semibold text-base truncate block">{{ $flower->name }}</span>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col justify-between p-3">
                        <div class="mb-2">
                            <span class="block text-xs text-gray-500">Kategori</span>
                            <span class="block text-sm font-medium text-black">{{ $flower->category->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="block text-xs text-gray-500">Deskripsi</span>
                            <span class="block text-sm text-black line-clamp-2">{{ $flower->description ?: '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="block text-xs text-gray-500">Harga per Tangkai</span>
                            <span class="block text-sm font-semibold text-green-700">
                                @php
                                    $stemPrice = $flower->prices->firstWhere('type', 'per_tangkai');
                                @endphp
                                @if($stemPrice)
                                    Rp{{ number_format($stemPrice->price,0,',','.') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="block text-xs text-gray-500">Harga per Ikat</span>
                            <span class="block text-sm font-semibold text-blue-700">
                                @php
                                    // Ambil harga per ikat prioritas: ikat_20, lalu ikat_10, lalu ikat_5
                                    $bundlePrice = $flower->prices->firstWhere('type', 'ikat_20')
                                        ?? $flower->prices->firstWhere('type', 'ikat_10')
                                        ?? $flower->prices->firstWhere('type', 'ikat_5');
                                @endphp
                                @if($bundlePrice)
                                    Rp{{ number_format($bundlePrice->price,0,',','.') }}
                                    <span class="text-xs text-gray-500">/{{ str_replace('_', ' ', $bundlePrice->type) }}</span>
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-xs text-gray-500">Stok Tersedia</span>
                            <span class="text-lg font-bold text-black">{{ $flower->current_stock }} Tangkai</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-8">Tidak ada bunga ready stock.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
