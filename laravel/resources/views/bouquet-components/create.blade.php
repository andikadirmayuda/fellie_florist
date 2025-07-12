<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 16v-4m8-4h-4m-8 0H4" /></svg>
            {{ __('Tambah Komponen Buket') }}
        </h2>
    </x-slot>

    <div class="py-6">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <h2>Tambah Komponen Buket</h2>
    <form action="{{ route('bouquet-components.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="bouquet_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Pilih Buket</label>
            <select name="bouquet_id" id="bouquet_id" class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" required>
                <option value="">-- Pilih Buket --</option>
                @foreach($bouquets as $bouquet)
                    <option value="{{ $bouquet->id }}">{{ $bouquet->name }}</option>
                @endforeach
            </select>
            @error('bouquet_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="size_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Pilih Ukuran</label>
            <select name="size_id" id="size_id" class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" required>
                <option value="">-- Pilih Ukuran --</option>
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
            @error('size_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <hr class="my-4 border-gray-300 dark:border-gray-700">
        <h5 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Daftar Komponen</h5>
        <div id="components-list">
            <!-- Baris komponen akan di-generate oleh JS -->
        </div>
        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mb-3" onclick="addComponentRow()">Tambah Komponen</button>
        <br>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Simpan</button>
    </form>
    <!-- TomSelect CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    </div>
    </div>

<script>
let componentIndex = 0;
const products = @json($products);

function addComponentRow() {
    let options = '<option value="">-- Pilih Produk --</option>';
    products.forEach(function(product) {
        options += `<option value="${product.id}">${product.name}</option>`;
    });
    const row = document.createElement('div');
    row.className = 'flex flex-wrap gap-2 mb-2 component-row';
    row.innerHTML = `
        <div class="w-full md:w-1/2">
            <select name="components[${componentIndex}][product_id]" class="product-select block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" required>
                ${options}
            </select>
        </div>
        <div class="w-2/5 md:w-1/3 hidden input-qty">
            <input type="number" name="components[${componentIndex}][quantity]" class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" min="1" placeholder="Jumlah" required>
        </div>
        <div class="w-1/5 md:w-1/6 flex items-center">
            <button type="button" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="removeComponentRow(this)">Hapus</button>
        </div>
    `;
    document.getElementById('components-list').appendChild(row);
    // Inisialisasi TomSelect pada select baru
    setTimeout(() => {
        const select = row.querySelector('select');
        new TomSelect(select, {create: false, sortField: 'text'});
        select.addEventListener('change', function() {
            const qtyDiv = row.querySelector('.input-qty');
            if (this.value) {
                qtyDiv.classList.remove('hidden');
            } else {
                qtyDiv.classList.add('hidden');
                qtyDiv.querySelector('input').value = '';
            }
        });
    }, 10);
    componentIndex++;
}
function removeComponentRow(btn) {
    const row = btn.closest('.component-row');
    if (row) row.remove();
}
// Tambahkan baris pertama saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    addComponentRow();
});
</script>
</x-app-layout>
