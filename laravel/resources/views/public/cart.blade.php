<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-4 px-2 sm:px-4">
        <div class="mb-4">
            <a href="/bunga-ready" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Ready Stock
            </a>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-pink-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.48 19h9.04a2 2 0 001.83-1.3L21 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7" /></svg>
            <span>Keranjang Belanja Anda</span>
        </h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(empty($cart))
            <div class="text-gray-600">Keranjang belanja kosong.</div>
        @else
    <div class="overflow-x-auto rounded shadow mb-4">
    <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-2 py-2 border font-semibold">Foto</th>
                    <th class="px-2 py-2 border font-semibold">Produk</th>
                    <th class="px-2 py-2 border font-semibold">Harga</th>
                    <th class="px-2 py-2 border font-semibold">Jumlah</th>
                    <th class="px-2 py-2 border font-semibold">Subtotal</th>
                    <th class="px-2 py-2 border font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $item)
                @php
                    $subtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                    $total += $subtotal;
                @endphp
                <tr class="hover:bg-pink-50 transition">
                    <td class="px-2 py-2 border text-center align-middle">
                        @if(!empty($item['image']))
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-14 h-14 sm:w-16 sm:h-16 object-cover rounded-lg shadow mx-auto border border-gray-200">
                        @else
                            <span class="inline-block w-14 h-14 sm:w-16 sm:h-16 bg-gray-200 rounded-lg"></span>
                        @endif
                    </td>
                    <td class="px-2 py-2 border align-middle">
                        <div class="font-semibold text-base sm:text-lg text-gray-800">{{ $item['product_name'] }}</div>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @if(!empty($item['price_type']))
                                <span class="inline-block bg-pink-100 text-pink-700 text-[10px] sm:text-xs px-2 py-0.5 rounded">Tipe: {{ $item['price_type'] }}</span>
                            @endif
                            @if(!empty($item['unit_equivalent']))
                                <span class="inline-block bg-gray-100 text-gray-700 text-[10px] sm:text-xs px-2 py-0.5 rounded">Satuan: {{ $item['unit_equivalent'] }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-2 py-2 border text-right align-middle font-semibold text-pink-700 whitespace-nowrap">Rp{{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                    <td class="px-2 py-2 border text-center align-middle">{{ $item['quantity'] }}</td>
                    <td class="px-2 py-2 border text-right align-middle font-semibold whitespace-nowrap">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td class="px-2 py-2 border text-center align-middle">
                        <form method="POST" action="{{ route('public.cart.remove', $item['product_id']) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:bg-red-50 hover:text-red-800 transition text-xs sm:text-sm px-2 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right px-2 py-2 border bg-gray-50 font-semibold">Total</th>
                    <th class="px-2 py-2 border text-right bg-gray-50 font-bold text-pink-700 whitespace-nowrap">Rp{{ number_format($total, 0, ',', '.') }}</th>
                    <th class="px-2 py-2 border bg-gray-50"></th>
                </tr>
            </tfoot>
        </table>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 w-full max-w-xs sm:max-w-none">
            <form method="POST" action="{{ route('public.cart.clear') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded shadow font-semibold tracking-wide">Kosongkan Keranjang</button>
            </form>
            <form method="GET" action="{{ route('public.checkout') }}" class="flex-1">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow font-semibold tracking-wide">Checkout</button>
            </form>
        </div>
    </div>
        @endif
    </div>
</body>
</html>
