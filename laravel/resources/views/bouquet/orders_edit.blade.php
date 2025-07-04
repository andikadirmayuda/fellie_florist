<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4 text-pink-600">Edit Pemesanan Buket</h1>
        <form method="POST" action="{{ route('bouquet.orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <label>Nama Pelanggan</label>
                <input type="text" name="customer_name" class="border rounded px-2 py-1 w-full" value="{{ old('customer_name', $order->customer_name) }}" required>
            </div>
            <div class="mb-2">
                <label>No. WhatsApp</label>
                <input type="text" name="wa_number" class="border rounded px-2 py-1 w-full" value="{{ old('wa_number', $order->wa_number) }}" required>
            </div>
            <div class="mb-2">
                <label>Catatan</label>
                <textarea name="notes" class="border rounded px-2 py-1 w-full">{{ old('notes', $order->notes) }}</textarea>
            </div>
            <div class="mb-2">
                <label>Komposisi Bunga</label>
                <ul>
                    @foreach($order->items as $item)
                    <li>{{ $item->product->name }} ({{ $item->quantity }})</li>
                    @endforeach
                </ul>
                <small>Untuk mengubah komposisi, hapus dan buat ulang pesanan.</small>
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('bouquet.orders.index') }}" class="ml-2 text-pink-600">Batal</a>
        </form>
    </div>
</x-app-layout>
