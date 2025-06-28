
<x-app-layout>
    <x-slot name="title">🌸 Daftar Bunga Ready Stock</x-slot>
    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />
        <style>
            body, .font-sans { font-family: 'Figtree', theme('fontFamily.sans'), sans-serif; }
        </style>
    </x-slot>
    <div class="min-h-screen bg-white text-black flex flex-col items-center py-10 px-2 font-sans">
        <div class="mt-2 text-sm text-gray-600 text-center">
            <i class="bi bi-clock-history mr-1"></i>Terakhir diperbarui: {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') : '-' }}
        </div>
        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center tracking-tight">
            <i class="bi bi-flower2 mr-2 text-pink-400"></i>Daftar Bunga Ready Stock
        </h1>
        <div class="w-full max-w-6xl mx-auto">
            <div class="grid grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse($flowers as $flower)
                <div class="bg-white shadow-lg rounded-sm hover:shadow-xl transition group flex flex-col overflow-hidden relative border border-gray-100 p-2 sm:p-3">
                    <div class="relative h-28 sm:h-32 md:h-36 w-full overflow-hidden flex items-center justify-center bg-black rounded-sm">
                        @if($flower->image)
                            <img src="{{ asset('storage/' . $flower->image) }}" alt="{{ $flower->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-400 text-3xl sm:text-4xl"><i class="bi bi-flower2"></i></div>
                        @endif
                        <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-60 px-2 py-1">
                            <span class="text-white font-semibold text-xs sm:text-sm truncate block"><i class="bi bi-tag mr-1"></i>{{ $flower->name }}</span>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col justify-between p-2 sm:p-3">
                        <div class="mb-1">
                            <span class="block text-[10px] text-gray-500"><i class="bi bi-bookmark mr-1"></i>Kategori</span>
                            <span class="block text-xs font-medium text-black">{{ $flower->category->name ?? '-' }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="block text-[10px] text-gray-500"><i class="bi bi-card-text mr-1"></i>Deskripsi</span>
                            <span class="block text-xs text-black line-clamp-2">{{ $flower->description ?: '-' }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="block text-[10px] text-gray-500"><i class="bi bi-currency-dollar mr-1"></i>Harga per Tangkai</span>
                            <span class="block text-xs font-semibold text-green-700">
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
                        <div class="mb-1">
                            <span class="block text-[10px] text-gray-500"><i class="bi bi-currency-exchange mr-1"></i>Harga per Ikat</span>
                            <span class="block text-xs font-semibold text-blue-700">
                                @php
                                    // Ambil harga per ikat prioritas: ikat_20, lalu ikat_10, lalu ikat_5
                                    $bundlePrice = $flower->prices->firstWhere('type', 'ikat_20')
                                        ?? $flower->prices->firstWhere('type', 'ikat_10')
                                        ?? $flower->prices->firstWhere('type', 'ikat_5');
                                @endphp
                                @if($bundlePrice)
                                    Rp{{ number_format($bundlePrice->price,0,',','.') }}
                                    {{-- <span class="text-[10px] text-gray-500">/{{ str_replace('_', ' ', $bundlePrice->type) }}</span> --}}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-[10px] text-gray-500"><i class="bi bi-box2-heart mr-1"></i>Stok Tersedia</span>
                            <span class="text-xs sm:text-sm font-bold text-black">{{ $flower->current_stock }} <span class="font-normal">Tangkai</span></span>
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
