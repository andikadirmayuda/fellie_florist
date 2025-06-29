<x-app-layout>
    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />
        <style>
            body, .font-sans { font-family: 'Figtree', theme('fontFamily.sans'), sans-serif; }
        </style>
    </x-slot>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight font-sans flex items-center">
                <i class="bi bi-people-fill mr-2 text-indigo-500"></i> Daftar Customer
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('customers.trashed') }}" class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-600 border border-transparent rounded-sm font-semibold text-xs sm:text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="bi bi-trash3 mr-1"></i> <span class="hidden xs:inline">{{ __('Sampah') }}</span>
                </a>
                <a href="{{ route('customers.create') }}" class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-indigo-600 border border-transparent rounded-sm font-semibold text-xs sm:text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="bi bi-person-plus-fill mr-1"></i> <span class="hidden xs:inline">{{ __('Tambah Customer') }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6  font-sans">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-sm border border-gray-100">
                <div class="p-4 sm:p-6 lg:p-8 text-gray-900">
                    <!-- Search and Filter -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col md:flex-row gap-2 md:gap-4">
                            <div class="flex-1 min-w-0 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white text-sm"><i class="bi bi-search"></i></span>
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Cari nama, email, atau telepon..."
                                    value="{{ request('search') }}"
                                    class="w-full rounded-sm shadow-md border border-gray-900 bg-black text-white placeholder-white font-sans text-sm pl-9 py-2 focus:border-indigo-400 focus:ring-indigo-400"
                                />
                            </div>
                            <div class="w-full md:w-48 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700 text-xs"><i class="bi bi-award"></i></span>
                                <select name="type" class="w-full rounded-sm shadow-md border border-gray-900 bg-white text-black font-sans text-sm pl-9 py-2 focus:border-indigo-400 focus:ring-indigo-400">
                                    <option value="">Semua Tipe</option>
                                    @foreach($customerTypes as $type)
                                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button type="submit" class="rounded-sm shadow-md font-sans text-sm flex items-center justify-center w-full md:w-auto">
                                <i class="bi bi-search mr-1"></i> <span class="hidden sm:inline">{{ __('Cari') }}</span>
                            </x-primary-button>
                        </form>
                    </div>

                    <!-- Customers Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 font-sans text-xs sm:text-sm">
                            <thead>
                                <tr>
                                    <th class="px-2 sm:px-4 py-3 bg-white text-left whitespace-nowrap">
                                        <span class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-person-badge mr-1"></i>Nama</span>
                                    </th>
                                    <th class="px-2 sm:px-4 py-3 bg-white text-left whitespace-nowrap">
                                        <span class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-telephone mr-1"></i>Kontak</span>
                                    </th>
                                    <th class="px-2 sm:px-4 py-3 bg-white text-left whitespace-nowrap hidden xs:table-cell">
                                        <span class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-award mr-1"></i>Tipe</span>
                                    </th>
                                    <th class="px-2 sm:px-4 py-3 bg-white text-left whitespace-nowrap hidden md:table-cell">
                                        <span class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-geo-alt mr-1"></i>Alamat</span>
                                    </th>
                                    <th class="px-2 sm:px-4 py-3 bg-white whitespace-nowrap text-right">
                                        <span class="text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center"><i class="bi bi-gear mr-1"></i>Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($customers as $customer)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-2 sm:px-4 py-4 whitespace-nowrap align-top">
                                            <div class="text-xs sm:text-sm font-semibold text-gray-900 flex items-center mb-1"><i class="bi bi-person-circle mr-2 text-indigo-400"></i>{{ $customer->name }}</div>
                                            <div class="block md:hidden text-xs text-gray-700 mb-1 flex items-center"><i class="bi bi-geo-alt mr-1 text-gray-400"></i>{{ $customer->full_address ?: '-' }}</div>
                                            <div class="block md:hidden mb-1">{!! $customer->type_badge ?? '<span class=\"text-gray-400 italic\">-</span>' !!}</div>
                                            <div class="block md:hidden flex flex-wrap gap-1 mt-2">
                                                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center text-indigo-600 hover:text-white hover:bg-indigo-600 font-semibold px-2 py-1 rounded-sm bg-indigo-50 shadow transition text-xs"><i class="bi bi-pencil-square mr-1"></i>Edit</a>
                                                <a href="{{ route('customers.history', $customer->id) }}" class="inline-flex items-center text-green-600 hover:text-white hover:bg-green-600 font-semibold px-2 py-1 rounded-sm bg-green-50 shadow transition text-xs"><i class="bi bi-clock-history mr-1"></i>Riwayat</a>
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block delete-customer-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="inline-flex items-center text-red-600 hover:text-white hover:bg-red-600 font-semibold px-2 py-1 rounded-sm bg-red-50 shadow transition text-xs btn-modal-delete">
                                                        <i class="bi bi-trash3 mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-2 sm:px-4 py-4 whitespace-nowrap align-top">
                                            <div class="text-xs sm:text-sm text-gray-900 flex items-center mb-1"><i class="bi bi-telephone mr-2 text-gray-400"></i>{{ $customer->phone }}</div>
                                            @if($customer->email)
                                                <div class="text-[10px] sm:text-xs text-gray-500 flex items-center"><i class="bi bi-envelope mr-1"></i>{{ $customer->email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-2 sm:px-4 py-4 whitespace-nowrap hidden xs:table-cell align-top">
                                            {!! $customer->type_badge ?? '<span class="text-gray-400 italic">-</span>' !!}
                                        </td>
                                        <td class="px-2 sm:px-4 py-4 hidden md:table-cell align-top">
                                            <div class="flex flex-col gap-1">
                                                <div class="text-xs sm:text-sm text-gray-900 flex items-center"><i class="bi bi-geo-alt mr-2 text-gray-400"></i>{{ $customer->full_address ?: '-' }}</div>
                                                <div class="mt-1">{!! $customer->type_badge ?? '<span class=\"text-gray-400 italic\">-</span>' !!}</div>
                                            </div>
                                        </td>
                                        <td class="px-2 sm:px-4 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium align-top hidden md:table-cell">
                                            <div class="flex flex-col sm:flex-row justify-end items-end sm:items-center gap-2 sm:gap-2">
                                                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center text-indigo-600 hover:text-white hover:bg-indigo-600 font-semibold px-3 py-1 rounded-sm bg-indigo-50 shadow transition text-xs sm:text-sm"><i class="bi bi-pencil-square mr-1"></i>Edit</a>
                                                <a href="{{ route('customers.history', $customer->id) }}" class="inline-flex items-center text-green-600 hover:text-white hover:bg-green-600 font-semibold px-3 py-1 rounded-sm bg-green-50 shadow transition text-xs sm:text-sm"><i class="bi bi-clock-history mr-1"></i>Riwayat</a>
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block delete-customer-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="inline-flex items-center text-red-600 hover:text-white hover:bg-red-600 font-semibold px-3 py-1 rounded-sm bg-red-50 shadow transition text-xs sm:text-sm btn-modal-delete">
                                                        <i class="bi bi-trash3 mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-2 sm:px-4 py-4 text-center text-gray-500">
                                            Tidak ada data customer
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 sm:mt-6">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Konfirmasi Hapus Alpine.js -->
<div x-data="{ open: false, form: null }" @open-modal-delete.window="open = true; form = $event.detail.form" class="">
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" style="display: none;">
        <div class="bg-white rounded-sm shadow-lg p-8 max-w-md w-full text-center border">
            <div class="flex flex-col items-center justify-center mb-4">
                <i class="bi bi-exclamation-circle text-6xl text-orange-300 mb-2"></i>
                <span class="text-black text-xl font-bold mb-2">Apakah Anda yakin?</span>
                <span class="text-gray-600 text-sm mb-2">Data yang dihapus tidak dapat dikembalikan!</span>
            </div>
            <div class="flex justify-center gap-4 mt-6">
                <button @click="if(form){ form.submit(); open = false; }" class="inline-flex items-center gap-2 bg-black text-white rounded-sm px-5 py-2 hover:bg-gray-900 font-semibold text-sm">
                    <i class="bi bi-trash3"></i> Ya, hapus!
                </button>
                <button @click="open = false" class="inline-flex items-center gap-2 bg-gray-200 text-black rounded-sm px-5 py-2 hover:bg-gray-300 font-semibold text-sm">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-modal-delete').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = btn.closest('form');
                window.dispatchEvent(new CustomEvent('open-modal-delete', { detail: { form } }));
            });
        });
    });
</script>
</x-app-layout>
