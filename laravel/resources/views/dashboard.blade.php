<x-app-layout>
    <!-- Bootstrap Icons & Figtree Font -->
    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />
        <style>
            body, .font-sans { font-family: 'Figtree', theme('fontFamily.sans'), sans-serif; }
        </style>
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight font-sans">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-white min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Cards with Icons -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center min-h-[140px] border border-gray-100 transition hover:shadow-xl">
                    <span class="mb-2"><i class="bi bi-people text-4xl text-black/60"></i></span>
                    <span class="text-3xl font-bold text-black">{{ $totalCustomers ?? 0 }}</span>
                    <span class="text-gray-500 mt-2">Pelanggan</span>
                </div>
                <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center min-h-[140px] border border-gray-100 transition hover:shadow-xl">
                    <span class="mb-2"><i class="bi bi-box-seam text-4xl text-black/60"></i></span>
                    <span class="text-3xl font-bold text-black">{{ $totalProducts ?? 0 }}</span>
                    <span class="text-gray-500 mt-2">Produk</span>
                </div>
                <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center min-h-[140px] border border-gray-100 transition hover:shadow-xl">
                    <span class="mb-2"><i class="bi bi-cart text-4xl text-black/60"></i></span>
                    <span class="text-3xl font-bold text-black">{{ $totalOrders ?? 0 }}</span>
                    <span class="text-gray-500 mt-2">Pesanan</span>
                </div>
                <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center min-h-[140px] border border-gray-100 transition hover:shadow-xl">
                    <span class="mb-2"><i class="bi bi-cash-stack text-4xl text-black/60"></i></span>
                    <span class="text-3xl font-bold text-black">{{ number_format($totalSales ?? 0,0,',','.') }}</span>
                    <span class="text-gray-500 mt-2">Penjualan</span>
                </div>
            </div>

            <!-- Grafik Penjualan & Pesanan (Chart.js) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <div class="bg-white rounded-2xl shadow-lg flex flex-col p-8 border border-gray-100">
                    <h3 class="font-semibold text-lg text-black mb-6 flex items-center"><i class="bi bi-graph-up-arrow mr-2 text-black/60"></i> Sales Performance</h3>
                    <canvas id="salesChart" class="w-full h-40"></canvas>
                </div>
                <div class="bg-white rounded-2xl shadow-lg flex flex-col p-8 border border-gray-100">
                    <h3 class="font-semibold text-lg text-black mb-6 flex items-center"><i class="bi bi-pie-chart mr-2 text-black/60"></i> Popular Categories</h3>
                    <canvas id="ordersChart" class="w-full h-40"></canvas>
                </div>
            </div>

            <!-- Notifikasi & Quick Action -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <div class="lg:col-span-2 bg-white shadow-lg rounded-2xl p-8 mb-6 lg:mb-0 border border-gray-100">
                    <h3 class="font-semibold text-lg text-black mb-4 flex items-center"><i class="bi bi-bell mr-2 text-black/60"></i> Notifikasi</h3>
                    <ul class="space-y-2">
                        @forelse($lowStockProducts ?? [] as $product)
                            @if(is_object($product))
                                <li class="text-red-600 flex items-center"><i class="bi bi-exclamation-triangle mr-2 text-black/60"></i>Stok menipis: {{ data_get($product, 'name', '-') }} ({{ data_get($product, 'stock', 0) }})</li>
                            @endif
                        @empty
                            <li class="text-gray-500 flex items-center"><i class="bi bi-info-circle mr-2 text-black/60"></i>Tidak ada notifikasi stok menipis.</li>
                        @endforelse
                        @forelse($recentOrders ?? [] as $order)
                            @if(is_object($order))
                                <li class="text-blue-700 flex items-center"><i class="bi bi-cart-plus mr-2 text-black/60"></i>Pesanan baru: #{{ data_get($order, 'id', '-') }} oleh {{ data_get($order, 'customer.name', '-') }}</li>
                            @endif
                        @empty
                        @endforelse
                    </ul>
                </div>
                <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col justify-between border border-gray-100">
                    <h3 class="font-semibold text-lg text-black mb-4 flex items-center"><i class="bi bi-lightning-charge mr-2 text-black/60"></i> Aksi Cepat</h3>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('products.create') }}" class="bg-black text-white font-semibold px-4 py-2 rounded-full hover:bg-gray-100 transition flex items-center justify-center"><i class="bi bi-plus mr-2"></i>Tambah Produk</a>
                        <a href="{{ route('orders.create') }}" class="bg-black text-white font-semibold px-4 py-2 rounded-full hover:bg-gray-100 transition flex items-center justify-center"><i class="bi bi-plus mr-2"></i>Buat Pesanan</a>
                    </div>
                </div>
            </div>

            <!-- Tabel Bunga Ready Stock -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="font-semibold text-lg text-black mb-4 flex items-center"><i class="bi bi-flower2 mr-2 text-black/60"></i> Bunga Ready Stock
                    <span class="ml-2 text-base font-normal">| <a href="{{ url('/bunga-ready') }}" target="_blank" class="text-blue-700 underline hover:text-blue-900 transition">Lihat Publik</a></span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-2 text-black font-semibold">Nama Bunga</th>
                                <th class="px-4 py-2 text-black font-semibold">Kategori</th>
                                <th class="px-4 py-2 text-black font-semibold">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($readyProducts ?? []) as $product)
                                @if(is_object($product))
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-2 text-black">{{ data_get($product, 'name', '-') }}</td>
                                    <td class="px-4 py-2 text-black">{{ data_get($product, 'category.name', '-') }}</td>
                                    <td class="px-4 py-2 text-black">{{ data_get($product, 'current_stock', 0) }}</td>
                                </tr>
                                @endif
                            @empty
                                <tr><td colspan="3" class="px-4 py-2 text-black text-center">Tidak ada produk ready stock.</td></tr>
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
        // Line Chart (Sales)
        new Chart(salesCtx, {
            type: 'line',
            data: @json($salesChartData ?? ['labels'=>[], 'datasets'=>[]]),
            options: {
                responsive: true,
                plugins: {
                    legend: {display: false},
                },
                elements: {
                    line: {borderWidth: 3, borderColor: '#111', tension: 0.4},
                    point: {radius: 5, backgroundColor: '#111'}
                },
                scales: {
                    x: {ticks: {color: '#888'}},
                    y: {ticks: {color: '#888'}}
                }
            }
        });
        // Donut Chart (Orders)
        new Chart(ordersCtx, {
            type: 'doughnut',
            data: @json($ordersChartData ?? ['labels'=>[], 'datasets'=>[]]),
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {position: 'bottom', labels: {color: '#222', font: {size: 14}}},
                }
            }
        });
    </script>
</x-app-layout>