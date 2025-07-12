<nav class="space-y-2 font-sans">
    {{-- <div class="px-4 py-6 bg-black shadow-sm rounded-2xl border border-gray-100 mt-4 mx-2"> --}}
        <div class="px-4 py-6 bg-black border-r border-gray-700 h-full min-h-screen">
        <ul class="space-y-2">

            {{-- Dashboard: Semua user --}}
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot name="icon">
                    <i class="bi bi-house-door-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Dashboard') }}
            </x-sidebar-link>

            {{-- Pelanggan: owner, admin, customers service --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('customers service'))
            <x-sidebar-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                <x-slot name="icon">
                    <i class="bi bi-people-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Pelanggan') }}
            </x-sidebar-link>
            @endif

            {{-- Kategori & Produk: owner, admin, karyawan --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('karyawan'))
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
            @endif

            {{-- Master Buket: owner, admin, karyawan --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('karyawan'))
            <x-sidebar-link :href="route('bouquets.index')" :active="request()->routeIs('bouquets.*')">
                <x-slot name="icon">
                    <i class="bi bi-flower1 text-lg mr-1"></i>
                </x-slot>
                Master Buket
            </x-sidebar-link>
            @endif

            {{-- Kategori Buket: owner, admin --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
            <x-sidebar-link :href="route('bouquet-categories.index')" :active="request()->routeIs('bouquet-categories.*')">
                <x-slot name="icon">
                    <i class="bi bi-bookmark-heart text-lg mr-1"></i>
                </x-slot>
                Kategori Buket
            </x-sidebar-link>
            @endif

            {{-- Inventaris: owner, admin, karyawan --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('karyawan'))
            <x-sidebar-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                <x-slot name="icon">
                    <i class="bi bi-archive-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Inventaris') }}
            </x-sidebar-link>
            @endif

            {{-- Pesanan: semua kecuali owner bisa lihat, owner juga bisa --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('karyawan') || auth()->user()->hasRole('customers service'))
            <x-sidebar-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                <x-slot name="icon">
                    <i class="bi bi-cart-fill text-lg mr-1"></i>
                </x-slot>
                {{ __('Pesanan') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('bouquet.orders.index')" :active="request()->routeIs('bouquet.orders.*')">
                <x-slot name="icon">
                    <i class="bi bi-basket-fill text-lg mr-1"></i>
                </x-slot>
                Pesanan Buket
            </x-sidebar-link>
            @endif

            {{-- Pesanan Publik: owner, admin --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
            <x-sidebar-link :href="route('admin.public-orders.index')" :active="request()->routeIs('admin.public-orders.*')">
                <x-slot name="icon">
                    <i class="bi bi-globe2 text-lg mr-1"></i>
                </x-slot>
                Pesanan Publik
            </x-sidebar-link>
            @endif

            {{-- Riwayat Pesanan: owner, admin, kasir, customers service --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('karyawan') || auth()->user()->hasRole('customers service'))
            <x-sidebar-link :href="route('order-histories.index')" :active="request()->routeIs('order-histories.*')">
                <x-slot name="icon">
                    <i class="bi bi-clock-history text-lg mr-1"></i>
                </x-slot>
                {{ __('Riwayat Pesanan') }}
            </x-sidebar-link>
            @endif

            {{-- Pengaturan Arsip & Riwayat: owner, admin --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
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
            @endif

            {{-- Penjualan: owner, admin, kasir --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
            <x-sidebar-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
                <x-slot name="icon">
                    <i class="bi bi-cash-stack text-lg mr-1"></i>
                </x-slot>
                {{ __('Penjualan') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('bouquet.sales.index')" :active="request()->routeIs('bouquet.sales.*')">
                <x-slot name="icon">
                    <i class="bi bi-cash-coin text-lg mr-1"></i>
                </x-slot>
                Penjualan Buket
            </x-sidebar-link>
            @endif

            {{-- Order via WhatsApp: owner, admin, kasir, customers service --}}
            {{-- @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('customers service'))
            <x-sidebar-link :href="route('order.whatsapp.form')" :active="request()->routeIs('order.whatsapp.form')">
                <x-slot name="icon">
                    <i class="bi bi-whatsapp text-lg mr-1"></i>
                </x-slot>
                Order via WhatsApp
            </x-sidebar-link>
            @endif --}}

            {{-- Laporan: tampil sesuai role --}}
            <li class="mt-4 mb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</li>
            {{-- Laporan Penjualan: owner, admin, kasir --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
            <x-sidebar-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')">
                <x-slot name="icon">
                    <i class="bi bi-bar-chart-fill text-lg mr-1"></i>
                </x-slot>
                Laporan Penjualan
            </x-sidebar-link>
            @endif
            {{-- Laporan Pemesanan: owner, admin, kasir, customers service --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir') || auth()->user()->hasRole('customers service'))
            <x-sidebar-link :href="route('reports.orders')" :active="request()->routeIs('reports.orders')">
                <x-slot name="icon">
                    <i class="bi bi-receipt-cutoff text-lg mr-1"></i>
                </x-slot>
                Laporan Pemesanan
            </x-sidebar-link>
            @endif
            {{-- Laporan Stok: owner, admin --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
            <x-sidebar-link :href="route('reports.stock')" :active="request()->routeIs('reports.stock')">
                <x-slot name="icon">
                    <i class="bi bi-box2-heart text-lg mr-1"></i>
                </x-slot>
                Laporan Stok
            </x-sidebar-link>
            @endif
            {{-- Laporan Pelanggan: owner, admin, customers service --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('customers service'))
            <x-sidebar-link :href="route('reports.customers')" :active="request()->routeIs('reports.customers')">
                <x-slot name="icon">
                    <i class="bi bi-people-fill text-lg mr-1"></i>
                </x-slot>
                Laporan Pelanggan
            </x-sidebar-link>
            @endif
            {{-- Laporan Pendapatan: owner, admin --}}
            @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
            <x-sidebar-link :href="route('reports.income')" :active="request()->routeIs('reports.income')">
                <x-slot name="icon">
                    <i class="bi bi-cash-stack text-lg mr-1"></i>
                </x-slot>
                Laporan Pendapatan
            </x-sidebar-link>
            @endif
        </ul>
    </div>
</nav>
