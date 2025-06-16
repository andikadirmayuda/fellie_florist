<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Inventaris') }} - {{ $product->name }}
            </h2>
            <a href="{{ route('inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Kembali ke Inventaris') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Product Info -->
                    <div class="mb-6 grid grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="text-sm font-medium text-gray-500">Stok Saat Ini</h3>
                            <p class="mt-1 text-lg font-semibold">{{ $product->formatted_stock }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="text-sm font-medium text-gray-500">Stok Minimal</h3>
                            <p class="mt-1 text-lg font-semibold">
                                {{ number_format($product->min_stock) }} {{ $product->base_unit }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($product->needs_restock)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Low Stock
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    In Stock
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- History Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">                    <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Perubahan Jumlah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Referensi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $log->created_at->format('d M Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($log->source === 'purchase') bg-green-100 text-green-800
                                            @elseif($log->source === 'sale') bg-blue-100 text-blue-800
                                            @elseif($log->source === 'return') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($log->source) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm {{ $log->qty > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $log->formatted_quantity }} {{ $product->base_unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $log->reference_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $log->notes }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
