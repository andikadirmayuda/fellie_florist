<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daftar Kategori') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Sampah') }}
                </a>
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Tambah Kategori') }}
                </a>
            </div>
        </div>
    </x-slot>    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search and Filter -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input
                                    type="text"
                                    name="search"
                                    placeholder="Cari kode atau nama kategori..."
                                    value="{{ request('search') }}"
                                    class="w-full"
                                />
                            </div>
                            <div class="w-48">
                                <select name="type" 
                                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Semua Tipe</option>
                                </select>
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Cari') }}
                            </x-primary-button>
                        </form>
                    </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif                    <!-- Categories Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-right">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">                                @forelse ($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $category->code }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $category->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('categories.edit', $category) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Edit
                                                </a>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Tidak ada kategori yang ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>                    @if($categories->hasPages())
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
