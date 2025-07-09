<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8 px-2 sm:px-4 flex flex-col items-center">
        <div class="w-full flex flex-col items-center gap-6 mb-8">
            <div class="w-full flex flex-col items-center">
                <div class="flex flex-col items-center w-full">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <span class="text-pink-600 font-bold text-2xl md:text-3xl text-center">Keranjang Belanja Anda</span>
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(empty($cart))
            <div class="text-gray-600">Keranjang belanja kosong.</div>
        @else
        <div class="w-full max-w-4xl mx-auto flex flex-col gap-6 overflow-x-auto">
            <!-- Header Kolom -->
            <div class="hidden md:grid grid-cols-7 bg-white text-gray-700 font-semibold text-center rounded-lg shadow-sm py-3 px-0 border border-gray-200">
                <div class="col-span-1 flex items-center justify-center min-w-[90px]">Gambar</div>
                <div class="col-span-2 flex items-center justify-center min-w-[180px]">Nama Produk & Detail</div>
                <div class="col-span-1 flex items-center justify-center min-w-[110px]">Jumlah</div>
                <div class="col-span-1 flex items-center justify-center min-w-[110px]">Harga</div>
                <div class="col-span-1 flex items-center justify-center min-w-[110px]">Subtotal</div>
                <div class="col-span-1 flex items-center justify-center min-w-[70px]">Aksi</div>
            </div>
            @php $total = 0; @endphp
            <!-- Desktop Cart Items: Semua item dalam satu grid list, tidak ada card terpisah -->
            @foreach($cart as $key => $item)
            @php
                $subtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                $total += $subtotal;
                $itemKey = $key;
            @endphp
            <div class="hidden md:grid grid-cols-7 items-center min-h-[90px] w-full max-w-full bg-white border-b border-gray-200 last:rounded-b-xl first:rounded-t-xl">
                <!-- Gambar -->
                <div class="col-span-1 flex items-center justify-center min-w-[90px] max-w-[90px] overflow-hidden">
                    @if(!empty($item['image']))
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg border border-gray-200" />
                    @else
                        <span class="inline-block w-16 h-16 md:w-20 md:h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-8 md:w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.293 9.293a1 1 0 011.414 0l1 1a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l1-1a1 1 0 011.414 0L12 15.586l4.293-4.293z" /></svg>
                        </span>
                    @endif
                </div>
                <!-- Nama Produk & Detail -->
                <div class="col-span-2 flex flex-col justify-center min-w-[180px] max-w-[260px] overflow-hidden">
                    <span class="font-semibold text-base md:text-base text-gray-800 mb-1 truncate block w-full whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $item['product_name'] }}">{{ $item['product_name'] }}</span>
                    <span class="text-xs text-gray-500 truncate block w-full whitespace-nowrap overflow-hidden text-ellipsis">Satuan: <span class="font-semibold text-gray-700">{{ $item['unit_equivalent'] ?? '-' }}</span></span>
                    <span class="text-xs text-gray-500 truncate block w-full whitespace-nowrap overflow-hidden text-ellipsis">Tipe: <span class="font-semibold text-gray-700">{{ $item['price_type'] ?? '-' }}</span></span>
                    <span class="text-xs text-green-600 mt-1 truncate block w-full whitespace-nowrap overflow-hidden text-ellipsis">Stok: <span class="font-semibold">{{ $item['stock'] ?? '-' }}</span></span>
                </div>
                <!-- Jumlah (desktop) -->
                <div class="col-span-1 flex items-center justify-center min-w-[110px] max-w-[110px] overflow-hidden">
                    <div class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded-lg px-1 py-1 gap-0 shadow-sm w-[110px] h-12">
                        <button type="button" class="decrement-qty text-gray-600 hover:text-pink-600 text-2xl font-bold focus:outline-none transition flex items-center justify-center" style="width:32px;height:32px;" data-id="{{ $itemKey }}" aria-label="Kurangi jumlah">-</button>
                        <span class="text-gray-800 text-lg font-semibold select-none flex items-center justify-center" style="width:32px;height:32px;text-align:center;">{{ $item['quantity'] }}</span>
                        <button type="button" class="increment-qty text-gray-600 hover:text-pink-600 text-2xl font-bold focus:outline-none transition flex items-center justify-center" style="width:32px;height:32px;" data-id="{{ $itemKey }}" aria-label="Tambah jumlah">+</button>
                        <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" class="quantity-input" data-id="{{ $itemKey }}" />
                    </div>
                </div>
                <!-- Harga (desktop) -->
                <div class="col-span-1 flex items-center justify-center min-w-[110px] max-w-[110px] overflow-hidden">
                    <span class="bg-gray-50 text-gray-800 rounded px-4 py-2 font-semibold text-base border border-gray-200 item-price text-center min-w-[110px] max-w-[110px] overflow-hidden whitespace-nowrap text-ellipsis block" data-id="{{ $itemKey }}" data-price="{{ $item['price'] ?? 0 }}">Rp{{ number_format($item['price'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <!-- Subtotal (desktop) -->
                <div class="col-span-1 flex items-center justify-center min-w-[110px] max-w-[110px] overflow-hidden">
                    <span class="bg-gray-50 text-gray-800 rounded px-4 py-2 font-semibold text-base border border-gray-200 item-subtotal text-center min-w-[110px] max-w-[110px] overflow-hidden whitespace-nowrap text-ellipsis block" data-id="{{ $itemKey }}">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <!-- Hapus (desktop) -->
                <div class="col-span-1 flex items-center justify-center min-w-[70px] max-w-[70px] overflow-hidden">
                    <form method="POST" action="{{ route('public.cart.remove', $itemKey) }}">
                        @csrf
                        <button type="submit" class="bg-red-50 hover:bg-red-200 text-red-600 rounded-full p-2 transition flex items-center justify-center" title="Hapus item ini">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            <!-- Mobile stacked card -->
            @if(!empty($cart))
                @foreach($cart as $key => $item)
                @php $itemKey = $key; $subtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1); @endphp
                <div class="block md:hidden w-full">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 transition hover:shadow-xl px-3 py-3 mb-2">
                        <div class="flex gap-3 items-center mb-2">
                            <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center">
                                @if(!empty($item['image']))
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover rounded-lg border border-gray-200" />
                                @else
                                    <span class="inline-block w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.293 9.293a1 1 0 011.414 0l1 1a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l1-1a1 1 0 011.414 0L12 15.586l4.293-4.293z" /></svg>
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-col flex-1">
                                <span class="font-semibold text-base text-gray-800 mb-1">{{ $item['product_name'] }}</span>
                                <span class="text-xs text-gray-500">Satuan: <span class="font-semibold text-gray-700">{{ $item['unit_equivalent'] ?? '-' }}</span></span>
                                <span class="text-xs text-gray-500">Tipe: <span class="font-semibold text-gray-700">{{ $item['price_type'] ?? '-' }}</span></span>
                                <span class="text-xs text-green-600 mt-1">Stok: <span class="font-semibold">{{ $item['stock'] ?? '-' }}</span></span>
                            </div>
                        </div>
                        <div class="flex flex-row flex-wrap gap-2 items-center justify-between mt-2">
                            <div class="flex items-center bg-gray-100 border border-gray-300 rounded-lg px-1 py-1 gap-0 shadow-sm w-[80px] h-8">
                                <button type="button" class="decrement-qty text-gray-600 hover:text-pink-600 text-base font-bold focus:outline-none transition flex items-center justify-center" style="width:22px;height:22px;" data-id="{{ $itemKey }}" aria-label="Kurangi jumlah">-</button>
                                <span class="text-gray-800 text-base font-semibold select-none flex items-center justify-center" style="width:22px;height:22px;text-align:center;">{{ $item['quantity'] }}</span>
                                <button type="button" class="increment-qty text-gray-600 hover:text-pink-600 text-base font-bold focus:outline-none transition flex items-center justify-center" style="width:22px;height:22px;" data-id="{{ $itemKey }}" aria-label="Tambah jumlah">+</button>
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" class="quantity-input" data-id="{{ $itemKey }}" />
                            </div>
                            <span class="bg-gray-50 text-gray-800 rounded px-2 py-1 font-semibold text-xs border border-gray-200 item-price text-center min-w-[60px]" data-id="{{ $itemKey }}" data-price="{{ $item['price'] ?? 0 }}">Rp{{ number_format($item['price'] ?? 0, 0, ',', '.') }}</span>
                            <span class="bg-gray-50 text-gray-800 rounded px-2 py-1 font-semibold text-xs border border-gray-200 item-subtotal text-center min-w-[60px]" data-id="{{ $itemKey }}">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            <form method="POST" action="{{ route('public.cart.remove', $itemKey) }}">
                                @csrf
                                <button type="submit" class="bg-red-50 hover:bg-red-200 text-red-600 rounded-full p-1 transition flex items-center justify-center" title="Hapus item ini">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            <!-- Total (Desktop/Tablet) -->
            <div class="hidden md:flex w-full justify-center mt-4">
                <div class="bg-white rounded-xl shadow border border-gray-100 px-8 py-6 flex flex-col items-center w-full max-w-xl">
                    <div class="flex flex-row justify-between items-center w-full border-t border-gray-200 pt-2">
                        <span class="font-semibold text-xl text-gray-700">Total</span>
                        <span class="font-bold text-2xl text-pink-700 cart-total">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <!-- Total Mobile -->
            <div class="md:hidden flex flex-col items-center gap-2 mt-4 w-full">
                <div class="bg-white rounded-xl shadow border border-gray-100 px-6 py-5 flex flex-col items-center max-w-xs w-full mx-auto">
                    <div class="w-full flex flex-col items-center gap-1">
                        <span class="font-semibold text-lg text-gray-700 text-center">Total</span>
                        <span class="font-bold text-2xl text-pink-700 cart-total text-center">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <!-- Tombol Aksi -->
            <div class="w-full flex flex-col md:flex-row gap-4 mt-8 justify-center items-center">
                <a href="/bunga-ready" class="w-full md:w-auto bg-white hover:bg-pink-50 text-pink-600 font-semibold py-3 px-6 rounded shadow border border-pink-200 transition text-center">Kembali Ready Stock</a>
                <form method="POST" action="{{ route('public.cart.clear') }}" class="w-full md:w-auto">
                    @csrf
                    <button type="submit" class="w-full md:w-auto bg-white hover:bg-pink-50 text-pink-600 font-semibold py-3 px-6 rounded shadow border border-pink-200 transition">Kosongkan Keranjang</button>
                </form>
                <form method="GET" action="{{ route('public.checkout') }}" class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded shadow border border-pink-700 transition">Selesaikan Pembayaran</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    function formatRupiah(angka) {
        return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    function updateCartQty(productId, newQty) {
        $.ajax({
            url: '/cart/update/' + productId,
            method: 'POST',
            data: {
                quantity: newQty,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                // Update semua input quantity (desktop & mobile) dengan data-id yang sama
                $('.quantity-input[data-id="' + productId + '"]').val(newQty);
                // Update tampilan span jumlah (desktop & mobile)
                $('.decrement-qty[data-id="' + productId + '"]').siblings('span').text(newQty);
                $('.increment-qty[data-id="' + productId + '"]').siblings('span').text(newQty);
                // Update subtotal item (desktop & mobile)
                var price = parseInt($('.item-price[data-id="' + productId + '"]').first().data('price'));
                var subtotal = price * newQty;
                $('.item-subtotal[data-id="' + productId + '"]').text(formatRupiah(subtotal));
                // Update total seluruh cart (hanya hitung satu per itemKey)
                var total = 0;
                var counted = {};
                $('.quantity-input').each(function() {
                    var pid = $(this).data('id');
                    if (counted[pid]) return; // skip duplicate (desktop/mobile)
                    counted[pid] = true;
                    var qty = parseInt($(this).val()) || 1;
                    var p = parseInt($('.item-price[data-id="' + pid + '"]').first().data('price'));
                    total += qty * p;
                });
                $('.cart-total').text(formatRupiah(total));
            },
            error: function(xhr) {
                alert('Gagal update jumlah.');
            }
        });
    }

    $('.increment-qty').click(function() {
        var id = $(this).data('id');
        // Ambil value dari salah satu input quantity (desktop atau mobile)
        var input = $('.quantity-input[data-id="' + id + '"]').first();
        var val = parseInt(input.val()) || 1;
        var newVal = val + 1;
        updateCartQty(id, newVal);
    });
    $('.decrement-qty').click(function() {
        var id = $(this).data('id');
        var input = $('.quantity-input[data-id="' + id + '"]').first();
        var val = parseInt(input.val()) || 1;
        if(val > 1) {
            var newVal = val - 1;
            updateCartQty(id, newVal);
        }
    });
});
</script>
</html>
