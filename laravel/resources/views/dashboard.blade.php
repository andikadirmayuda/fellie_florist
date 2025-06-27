<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white dark:bg-black min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-black dark:bg-white shadow rounded-2xl p-6 flex flex-col items-center transition-colors">
                    <span class="text-3xl font-bold text-white dark:text-black">{{ $totalCustomers ?? 0 }}</span>
                    <span class="text-gray-400 dark:text-gray-600 mt-2">Pelanggan</span>
                </div>
                <div class="bg-black dark:bg-white shadow rounded-2xl p-6 flex flex-col items-center transition-colors">
                    <span class="text-3xl font-bold text-white dark:text-black">{{ $totalProducts ?? 0 }}</span>
                    <span class="text-gray-400 dark:text-gray-600 mt-2">Produk</span>
                </div>
                <div class="bg-black dark:bg-white shadow rounded-2xl p-6 flex flex-col items-center transition-colors">
                    <span class="text-3xl font-bold text-white dark:text-black">{{ $totalOrders ?? 0 }}</span>
                    <span class="text-gray-400 dark:text-gray-600 mt-2">Pesanan</span>
                </div>
                <div class="bg-black dark:bg-white shadow rounded-2xl p-6 flex flex-col items-center transition-colors">
                    <span class="text-3xl font-bold text-white dark:text-black">{{ number_format($totalSales ?? 0,0,',','.') }}</span>
                    <span class="text-gray-400 dark:text-gray-600 mt-2">Penjualan</span>
                </div>
            </div>

            <!-- Grafik Penjualan & Pesanan (Chart.js) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl h-56 flex flex-col justify-end p-6">
                    <h3 class="font-semibold text-lg text-black dark:text-white mb-8 mt-4">Grafik Penjualan</h3>
                    <canvas id="salesChart" class="w-full h-32"></canvas>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl h-56 flex flex-col justify-end p-6">
                    <h3 class="font-semibold text-lg text-black dark:text-white m-10">Grafik Pesanan</h3>
                    <canvas id="ordersChart" class="w-full h-32"></canvas>
                </div>
            </div>

            <!-- Notifikasi & Quick Action -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-black dark:bg-white shadow rounded-2xl p-6 mb-6 lg:mb-0">
                    <h3 class="font-semibold text-lg text-white dark:text-black mb-4">Notifikasi</h3>
                    <ul class="space-y-2">
                        @forelse($lowStockProducts ?? [] as $product)
                            <li class="text-red-500 dark:text-red-400">Stok menipis: {{ $product->name }} ({{ $product->stock }})</li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-300">Tidak ada notifikasi stok menipis.</li>
                        @endforelse
                        @forelse($recentOrders ?? [] as $order)
                            <li class="text-blue-500 dark:text-blue-400">Pesanan baru: #{{ $order->id }} oleh {{ $order->customer->name }}</li>
                        @empty
                        @endforelse
                    </ul>
                </div>
                <div class="bg-black dark:bg-white shadow rounded-2xl p-6 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg text-white dark:text-black mb-4">Aksi Cepat</h3>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('products.create') }}" class="bg-white text-black font-semibold px-4 py-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-900 transition">Tambah Produk</a>
                        <a href="{{ route('orders.create') }}" class="bg-white text-black font-semibold px-4 py-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-900 transition">Buat Pesanan</a>
                    </div>
                </div>
            </div>

            <!-- Tabel Bunga Ready Stock -->
            <div class="bg-black dark:bg-gray-900 rounded-2xl shadow p-6">
                <h3 class="font-semibold text-lg text-white mb-4">
                    Bunga Ready Stock
                    <span class="ml-2 text-base font-normal">
                        | <a href="{{ url('/bunga-ready') }}" target="_blank" class="text-blue-400 underline hover:text-blue-600 transition">Lihat Publik</a>
                    </span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="px-4 py-2 text-white font-semibold">Nama Bunga</th>
                                <th class="px-4 py-2 text-white font-semibold">Kategori</th>
                                <th class="px-4 py-2 text-white font-semibold">Stok</th>
                                {{-- <th class="px-4 py-2 text-white font-semibold">Harga</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($readyProducts ?? []) as $product)
                                @if(is_object($product))
                                <tr class="border-b border-gray-800 hover:bg-gray-800">
                                    <td class="px-4 py-2 text-white">{{ data_get($product, 'name', '-') }}</td>
                                    <td class="px-4 py-2 text-white">{{ data_get($product, 'category.name', '-') }}</td>
                                    <td class="px-4 py-2 text-white">{{ data_get($product, 'current_stock', 0) }}</td>
                                </tr>
                                @endif
                            @empty
                                <tr><td colspan="4" class="px-4 py-2 text-white text-center">Tidak ada produk ready stock.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: @json($salesChartData ?? ['labels'=>[], 'datasets'=>[]]),
            options: {responsive: true, plugins: {legend: {display: false}}}
        });
        new Chart(ordersCtx, {
            type: 'bar',
            data: @json($ordersChartData ?? ['labels'=>[], 'datasets'=>[]]),
            options: {responsive: true, plugins: {legend: {display: false}}}
        });
    </script>
</x-app-layout>