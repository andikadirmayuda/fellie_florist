<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8 max-w-lg">
        <h2 class="text-lg font-semibold mb-4">Checkout Pesanan</h2>
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if(empty($cart))
            <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
                Keranjang belanja kosong. <a href="{{ route('public.flowers') }}" class="underline">Kembali berbelanja</a>
            </div>
        @else
            <form method="POST" action="{{ route('public.checkout.process') }}" class="bg-white p-6 rounded shadow">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1">Nama Lengkap</label>
                    <input type="text" name="customer_name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1">No. WhatsApp</label>
                    <input type="text" name="wa_number" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1">Tanggal Ambil/Kirim</label>
                    <input type="date" name="pickup_date" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1">Waktu Ambil/Pengiriman</label>
                    <input type="time" name="pickup_time" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1">Metode Pengiriman</label>
                    <select name="delivery_method" class="w-full border rounded px-3 py-2" required>
                        <option value="Ambil Langsung">Ambil Langsung</option>
                        <option value="GoSend">GoSend</option>
                        <option value="Kurir Toko">Kurir Toko</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1">Tujuan Pengiriman</label>
                    <textarea name="destination" class="w-full border rounded px-3 py-2" rows="2" required></textarea>
                </div>
                <div class="mb-6">
                    <h3 class="font-bold mb-2">Ringkasan Keranjang</h3>
                    <table class="min-w-full bg-white border border-gray-200 mb-2 text-xs">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 border">Produk</th>
                                <th class="px-2 py-1 border">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                <tr>
                                    <td class="px-2 py-1 border">{{ $item['product_name'] }}</td>
                                    <td class="px-2 py-1 border text-center">{{ $item['quantity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded">Kirim
                    Pesanan</button>
            </form>
        @endif
    </div>
</body>

</html>