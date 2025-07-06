<x-app-layout>
    <x-slot name="header">
        Daftar Pesanan Publik
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter Bar Responsive -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                            <div>
                                <label for="filter-nama" class="block text-xs font-semibold text-gray-600 mb-1">Nama Pelanggan</label>
                                <input type="text" id="filter-nama" placeholder="Cari nama..." class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition" />
                            </div>
                            <div>
                                <label for="filter-tanggal" class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Kirim/Ambil</label>
                                <input type="date" id="filter-tanggal" class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition" />
                            </div>
                            <div>
                                <label for="filter-metode" class="block text-xs font-semibold text-gray-600 mb-1">Metode Pengiriman</label>
                                <select id="filter-metode" class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition">
                                    <option value="">Semua</option>
                                    <option value="delivery">Delivery</option>
                                    <option value="pickup">Pickup</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-status" class="block text-xs font-semibold text-gray-600 mb-1">Status Pesanan</label>
                                <select id="filter-status" class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition">
                                    <option value="">Semua</option>
                                    <option value="pending">Menunggu Diproses</option>
                                    <option value="processed">Diproses</option>
                                    <option value="packing">Dikemas</option>
                                    <option value="shipped">Dikirim</option>
                                    <option value="completed">Selesai</option>
                                    <option value="done">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-bayar" class="block text-xs font-semibold text-gray-600 mb-1">Status Pembayaran</label>
                                <select id="filter-bayar" class="w-full border border-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 rounded-lg px-3 py-2 text-sm transition">
                                    <option value="">Semua</option>
                                    <option value="waiting_confirmation">Menunggu Konfirmasi</option>
                                    <option value="ready_to_pay">Siap Dibayar</option>
                                    <option value="waiting_payment">Menunggu Pembayaran</option>
                                    <option value="waiting_verification">Menunggu Verifikasi</option>
                                    <option value="dp_paid">Dp</option>
                                    <option value="partial_paid">Sebagian Bayar</option>
                                    <option value="paid">Lunas</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                                <tr>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">ID</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Nama Pelanggan</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Tanggal Ambil/Kirim</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Metode Pengiriman</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Status Pesanan</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Status Bayar</th>
                                    <th class="px-4 py-2 border font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table-body" class="bg-white divide-y divide-gray-200 transition-all">
                                {{-- Data rows will be loaded here via AJAX --}}
                                @foreach($orders as $order)
                                    @include('admin.public_orders._order_row', ['order' => $order])
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4 flex justify-center" id="pagination-links">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const loading = document.createElement('tr');
        loading.innerHTML = `<td colspan="7" class="py-8 text-center text-blue-500 animate-pulse">Memuat data...</td>`;

        // Debounce util
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        function fetchOrders() {
            const params = {
                nama: document.getElementById('filter-nama').value,
                tanggal: document.getElementById('filter-tanggal').value,
                metode: document.getElementById('filter-metode').value,
                status: document.getElementById('filter-status').value,
                bayar: document.getElementById('filter-bayar').value,
            };
            const query = new URLSearchParams(params).toString();
            const tbody = document.getElementById('orders-table-body');
            tbody.innerHTML = '';
            tbody.appendChild(loading);
            fetch(`{{ route('admin.public-orders.filter') }}?${query}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = data.rows;
                    document.getElementById('pagination-links').innerHTML = data.pagination;
                });
        }

        // Debounce hanya untuk input nama (300ms), yang lain langsung
        document.getElementById('filter-nama').addEventListener('input', debounce(fetchOrders, 300));
        document.getElementById('filter-tanggal').addEventListener('change', fetchOrders);
        document.getElementById('filter-metode').addEventListener('change', fetchOrders);
        document.getElementById('filter-status').addEventListener('change', fetchOrders);
        document.getElementById('filter-bayar').addEventListener('change', fetchOrders);
    </script>
</x-app-layout>
