<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="bi bi-people-fill text-pink-500 mr-2"></i>
            {{ __('Daftar Pelanggan Online') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('online-customers.index') }}" class="flex gap-3">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari nama pelanggan atau nomor WhatsApp..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                                <i class="bi bi-search mr-1"></i> Cari
                            </button>
                            @if($search)
                                <a href="{{ route('online-customers.index') }}"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                    <i class="bi bi-x-circle mr-1"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-blue-100">Total Pelanggan</p>
                                    <p class="text-2xl font-bold">{{ $onlineCustomers->total() }}</p>
                                </div>
                                <i class="bi bi-people text-3xl text-blue-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-green-100">Total Pesanan</p>
                                    <p class="text-2xl font-bold">{{ $onlineCustomers->sum('total_orders') }}</p>
                                </div>
                                <i class="bi bi-bag-check text-3xl text-green-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-purple-100">Total Penjualan</p>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($onlineCustomers->sum('total_spent'), 0, ',', '.') }}
                                    </p>
                                </div>
                                <i class="bi bi-currency-dollar text-3xl text-purple-200"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Customers Table -->
                    @if($onlineCustomers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelanggan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kontak
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Pesanan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Belanja
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Terakhir Pesan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($onlineCustomers as $customer)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                                            <i class="bi bi-person-fill text-pink-500"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $customer->customer_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Bergabung:
                                                            {{ \Carbon\Carbon::parse($customer->first_order_date)->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <i class="bi bi-whatsapp text-green-500 mr-1"></i>
                                                    {{ $customer->wa_number }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $customer->total_orders }} pesanan
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($customer->last_order_date)->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('online-customers.show', $customer->wa_number) }}"
                                                        class="text-blue-600 hover:text-blue-900 transition">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('online-customers.edit', $customer->wa_number) }}"
                                                        class="text-green-600 hover:text-green-900 transition">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $onlineCustomers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-people text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pelanggan online</h3>
                            <p class="text-gray-500">
                                @if($search)
                                    Tidak ditemukan pelanggan dengan kata kunci "{{ $search }}"
                                @else
                                    Belum ada pelanggan yang melakukan pemesanan online
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>