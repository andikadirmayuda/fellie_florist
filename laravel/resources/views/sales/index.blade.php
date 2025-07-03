<x-app-layout>
    <x-slot name="header">
        <h2 class="font-sans text-xl text-black leading-tight"> <!-- font-sans & text-black -->
            <i class="bi bi-cash-coin mr-2"></i>Daftar Transaksi Penjualan
        </h2>
    </x-slot>
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <div class="max-w-5xl mx-auto py-8 px-2 sm:px-4 font-sans">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-sm mb-4 shadow">{{ session('success') }}</div>
        @endif
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-2">
            <h3 class="text-black text-xs font-semibold flex items-center"><i class="bi bi-list-ul mr-2"></i>Data Penjualan</h3>
            <a href="{{ route('sales.create') }}" class="bg-black text-white rounded-sm px-5 py-2 flex items-center gap-2 hover:bg-black/90 transition">
                <i class="bi bi-plus-circle"></i>
                Transaksi Baru
            </a>
        </div>
        <div class="bg-white shadow-lg rounded-sm border overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-black uppercase tracking-wider">No</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-black uppercase tracking-wider"><i class="bi bi-receipt mr-1"></i>No. Penjualan</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-black uppercase tracking-wider"><i class="bi bi-clock-history mr-1"></i>Waktu</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-black uppercase tracking-wider"><i class="bi bi-cash-stack mr-1"></i>Total</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-black uppercase tracking-wider"><i class="bi bi-credit-card mr-1"></i>Metode Pembayaran</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-black uppercase tracking-wider"><i class="bi bi-gear mr-1"></i>Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-2 text-black">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2 text-black font-semibold">{{ $sale->order_number }}</td>
                        <td class="px-3 py-2 text-black">{{ $sale->order_time }}</td>
                        <td class="px-3 py-2 text-black">{{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-black">{{ ucfirst($sale->payment_method) }}</td>
                        <td class="px-3 py-2 flex flex-wrap gap-2">
                            <a href="{{ route('sales.show', $sale->id) }}" class="bg-black text-white rounded-sm px-3 py-1 flex items-center gap-1 hover:bg-black/90 text-xs" title="Detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('sales.show', $sale->id) }}?print=1" class="bg-black text-white rounded-sm px-3 py-1 flex items-center gap-1 hover:bg-black/90 text-xs" title="Print Struk">
                                <i class="bi bi-printer"></i> Print
                            </a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                {{-- <button type="submit" class="bg-red-600 text-white rounded-sm px-3 py-1 flex items-center gap-1 hover:bg-red-800 text-xs" title="Hapus">
                                    <i class="bi bi-trash"></i> Hapus
                                </button> --}}
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi penjualan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-end">
            {{ $sales->links() }}
        </div>
    </div>
</x-app-layout>
