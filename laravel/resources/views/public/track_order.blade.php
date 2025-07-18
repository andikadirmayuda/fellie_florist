<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan Publik - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-2 sm:px-4 py-8 min-h-screen">
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-8 w-full max-w-2xl mx-auto">
            <h1 class="text-xl sm:text-2xl font-bold text-pink-600 mb-4 text-center">Lacak Pesanan Publik</h1>
            <form method="GET" action="{{ route('public.order.track') }}"
                class="flex flex-col sm:flex-row items-center gap-2 mb-6">
                <input type="text" name="wa_number" class="border rounded px-3 py-2 text-sm flex-1"
                    placeholder="Masukkan No. WhatsApp" value="{{ $wa_number ?? '' }}" required>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Lacak</button>
            </form>
            @if($wa_number && $orders->isEmpty())
                <div class="text-center text-red-500 mb-4">Tidak ada pesanan ditemukan untuk nomor WhatsApp tersebut.</div>
            @endif
            @if($orders->count())
                <div class="mb-2 text-gray-700 text-sm">Menampilkan {{ $orders->count() }} pesanan untuk:
                    <b>{{ $wa_number }}</b>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-base border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-2">Kode</th>
                                <th class="py-2 px-2">Tanggal</th>
                                <th class="py-2 px-2">Status</th>
                                <th class="py-2 px-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-t">
                                    <td class="py-2 px-2 font-mono">{{ $order->public_code }}</td>
                                    <td class="py-2 px-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="py-2 px-2">{{ ucfirst($order->status) }}</td>
                                    <td class="py-2 px-2">
                                        <a href="{{ route('public.order.detail', ['public_code' => $order->public_code]) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Lihat
                                            Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="w-full flex justify-center mt-4 mb-4">
            <a href="{{ route('public.flowers') }}"
                class="inline-flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white font-bold px-4 py-2 rounded-lg shadow transition">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar Bunga
            </a>
        </div>
    </div>
</body>

</html>