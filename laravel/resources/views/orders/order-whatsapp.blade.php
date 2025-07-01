<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Form Pemesanan & Kirim WhatsApp
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="waOrderForm" method="POST" action="{{ route('orders.store') }}" onsubmit="return handleOrderSubmit(event)">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-input w-full rounded-md border-gray-300" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="product" id="product" class="form-input w-full rounded-md border-gray-300" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->name }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" class="form-input w-full rounded-md border-gray-300" min="1" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="address" id="address" class="form-input w-full rounded-md border-gray-300" required></textarea>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Kirim Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function handleOrderSubmit(event) {
        event.preventDefault();
        // Submit form ke backend
        const form = document.getElementById('waOrderForm');
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Setelah sukses, redirect ke WhatsApp
            if(data.success && data.order) {
                const noWa = '6281234567890'; // Ganti dengan nomor admin
                let pesan = `Halo, saya sudah melakukan pemesanan.\n`;
                pesan += `Nama: ${data.order.customer_name}\n`;
                pesan += `Produk: ${data.order.product}\n`;
                pesan += `Jumlah: ${data.order.quantity}\n`;
                pesan += `Alamat: ${data.order.address}\n`;
                pesan += `No. Order: ${data.order.order_number || '-'}\n`;
                window.location.href = `https://wa.me/${noWa}?text=${encodeURIComponent(pesan)}`;
            } else {
                alert('Terjadi kesalahan saat menyimpan pesanan.');
            }
        })
        .catch(() => alert('Gagal mengirim data.'));
        return false;
    }
    </script>
</x-app-layout>
