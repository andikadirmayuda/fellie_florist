<x-app-layout>
    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />
        <style>
            body, .font-sans { font-family: 'Figtree', theme('fontFamily.sans'), sans-serif; }
        </style>
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-sans flex items-center">
                <i class="bi bi-people-fill mr-2 text-indigo-500"></i> Daftar Customer
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('customers.trashed') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="bi bi-trash3 mr-1"></i> {{ __('Sampah') }}
                </a>
                <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="bi bi-person-plus-fill mr-1"></i> {{ __('Tambah Customer') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-white font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    <!-- Search and Filter -->
                    <div class="mb-8">
                        <form method="GET" action="{{ route('customers.index') }}" class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input
                                    type="text"
                                    name="search"
                                    placeholder="Cari nama, email, atau telepon..."
                                    value="{{ request('search') }}"
                                    class="w-full"
                                />
                            </div>
                            <div class="w-48">
                                <select name="type" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm">
                                    <option value="">Semua Tipe</option>
                                    @foreach($customerTypes as $type)
                                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button type="submit">
                                <i class="bi bi-search mr-1"></i> {{ __('Cari') }}
                            </x-primary-button>
                        </form>
                    </div>

                    <!-- Customers Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-person-badge mr-1"></i>Nama</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-telephone mr-1"></i>Kontak</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-award mr-1"></i>Tipe</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-geo-alt mr-1"></i>Alamat</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-gear mr-1"></i>Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($customers as $customer)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 flex items-center"><i class="bi bi-person-circle mr-2 text-indigo-400"></i>{{ $customer->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 flex items-center"><i class="bi bi-telephone mr-2 text-gray-400"></i>{{ $customer->phone }}</div>
                                            @if($customer->email)
                                                <div class="text-xs text-gray-500 flex items-center"><i class="bi bi-envelope mr-1"></i>{{ $customer->email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {!! $customer->type_badge !!}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 flex items-center"><i class="bi bi-geo-alt mr-2 text-gray-400"></i>{{ $customer->full_address ?: '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-semibold px-3 py-1 rounded-full bg-indigo-50 hover:bg-indigo-100 transition"><i class="bi bi-pencil-square mr-1"></i>Edit</a>
                                                <a href="{{ route('customers.history', $customer->id) }}" class="inline-flex items-center text-green-600 hover:text-green-900 font-semibold px-3 py-1 rounded-full bg-green-50 hover:bg-green-100 transition"><i class="bi bi-clock-history mr-1"></i>Riwayat</a>
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900 font-semibold px-3 py-1 rounded-full bg-red-50 hover:bg-red-100 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
                                                        <i class="bi bi-trash3 mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data customer
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
