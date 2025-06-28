<nav class="space-y-2 font-sans">
    <div class="px-4 py-6 bg-white shadow-lg rounded-2xl border border-gray-100 mt-4 mx-2">
        <ul class="space-y-2">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot name="icon">
                    <i class="bi bi-house-door-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Dashboard') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                <x-slot name="icon">
                    <i class="bi bi-people-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Pelanggan') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                <x-slot name="icon">
                    <i class="bi bi-tags-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Kategori') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                <x-slot name="icon">
                    <i class="bi bi-box-seam text-lg mr-1"></i>
                </x-slot>
                {{ __('Produk') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                <x-slot name="icon">
                    <i class="bi bi-archive-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Inventaris') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                <x-slot name="icon">
                    <i class="bi bi-cart-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Pesanan') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('order-histories.index')" :active="request()->routeIs('order-histories.*')">
                <x-slot name="icon">
                    <i class="bi bi-clock-history text-lg mr-1"></i>
                </x-slot>
                {{ __('Riwayat Pesanan') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('settings.archive')" :active="request()->routeIs('settings.archive')">
                <x-slot name="icon">
                    <i class="bi bi-archive-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Pengaturan Arsip') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('settings.history')" :active="request()->routeIs('settings.history')">
                <x-slot name="icon">
                    <i class="bi bi-clock-history text-lg mr-1"></i>
                </x-slot>
                {{ __('Pengaturan Riwayat') }}
            </x-sidebar-link>

            <x-sidebar-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
                <x-slot name="icon">
                    <i class="bi bi-cash-stack text-lg mr-1"></i>
                </x-slot>
                {{ __('Penjualan') }}
            </x-sidebar-link>

            <li class="mt-4 mb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</li>
            <x-sidebar-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')">
                <x-slot name="icon">
                    <i class="bi bi-bar-chart-fill text-lg mr-1"></i>
                </x-slot>
                Laporan Penjualan
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports.orders')" :active="request()->routeIs('reports.orders')">
                <x-slot name="icon">
                    <i class="bi bi-receipt-cutoff text-lg mr-1"></i>
                </x-slot>
                Laporan Pemesanan
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports.stock')" :active="request()->routeIs('reports.stock')">
                <x-slot name="icon">
                    <i class="bi bi-box2-heart text-lg mr-1"></i>
                </x-slot>
                Laporan Stok
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports.customers')" :active="request()->routeIs('reports.customers')">
                <x-slot name="icon">
                    <i class="bi bi-people-fill text-lg mr-1"></i>
                </x-slot>
                Laporan Pelanggan
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports.income')" :active="request()->routeIs('reports.income')">
                <x-slot name="icon">
                    <i class="bi bi-cash-stack text-lg mr-1"></i>
                </x-slot>
                Laporan Pendapatan
            </x-sidebar-link>
        </ul>
    </div>
</nav>
