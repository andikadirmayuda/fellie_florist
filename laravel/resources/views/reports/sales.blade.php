<x-app-layout>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #f0fdf4 100%);
        }
        .section-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.1);
            transition: all 0.3s ease;
        }
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(244, 63, 94, 0.1);
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .form-enter {
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-pink-100 rounded-xl mr-3">
                    <i class="bi bi-graph-up text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
                    <p class="text-sm text-gray-500 mt-1">Analisis transaksi dan performa penjualan</p>
                </div>
            </div>
            <form method="GET" class="flex flex-wrap items-end gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                <div class="flex flex-wrap gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            <i class="bi bi-calendar3 mr-1 text-pink-500"></i>
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" value="{{ $start }}" 
                               class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            <i class="bi bi-calendar3 mr-1 text-pink-500"></i>
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="{{ $end }}" 
                               class="px-3 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-sm">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="h-9 px-4 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                        <i class="bi bi-funnel mr-1.5"></i>
                        Filter
                    </button>
                    @if(!auth()->user()->hasRole('kasir'))
                    <a href="{{ route('reports.sales.pdf', request()->all()) }}" 
                       class="h-9 px-4 bg-white border border-gray-200 hover:border-pink-500 hover:bg-pink-50 text-gray-700 hover:text-pink-600 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center">
                        <i class="bi bi-file-pdf mr-1.5"></i>
                        Export PDF
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </x-slot>
    
    <div class="py-8 gradient-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards Statistik -->
            @if(auth()->user()->hasRole(['owner']))
            <div class="stats-card p-6 relative overflow-hidden mb-6">
                <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                    <i class="bi bi-cash-coin text-8xl text-green-500 transform translate-x-8 -translate-y-8"></i>
                </div>
                <div class="relative">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                            <i class="bi bi-cash-coin text-xl text-white"></i>
                        </div>
                        <p class="ml-3 text-sm font-medium text-gray-500">Total Pendapatan</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalPendapatan,0,',','.') }}</p>
                    <div class="flex items-center mt-2">
                        <i class="bi bi-calendar3 text-xs text-gray-400 mr-1"></i>
                        <p class="text-xs text-gray-500">{{ date('d M Y', strtotime($start)) }} - {{ date('d M Y', strtotime($end)) }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-receipt text-8xl text-blue-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="bi bi-receipt text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Total Transaksi</p>
                        </div>
                        <div class="text-2xl font-bold text-gray-800">{{ $totalTransaksi }}</div>
                        <div class="flex items-center mt-2">
                            <i class="bi bi-check-circle text-xs text-green-500 mr-1"></i>
                            <p class="text-xs text-gray-500">Transaksi sukses</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-star text-8xl text-pink-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg">
                                <i class="bi bi-star text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Produk Terlaris</p>
                        </div>
                        @if($produkTerlaris)
                            <p class="text-lg font-bold text-gray-800 line-clamp-1">{{ $produkTerlaris->name }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center">
                                    <i class="bi bi-bag-check text-xs text-pink-500 mr-1"></i>
                                    <span class="text-sm text-gray-600">{{ $produkTerlaris->total_terjual }} terjual</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-tag text-xs text-gray-400 mr-1"></i>
                                    <span class="text-xs text-gray-500">Rp{{ number_format($produkTerlaris->price,0,',','.') }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400">Tidak ada data</p>
                        {{-- @endif --}}
                    </div>
                </div>

                <div class="stats-card p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-10">
                        <i class="bi bi-graph-down text-8xl text-orange-500 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg">
                                <i class="bi bi-graph-down text-xl text-white"></i>
                            </div>
                            <p class="ml-3 text-sm font-medium text-gray-500">Penjualan Terendah</p>
                        </div>
                        @if($produkKurangLaku)
                            <p class="text-lg font-bold text-gray-800 line-clamp-1">{{ $produkKurangLaku->name }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center">
                                    <i class="bi bi-bag text-xs text-orange-500 mr-1"></i>
                                    <span class="text-sm text-gray-600">{{ $produkKurangLaku->total_terjual }} terjual</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-tag text-xs text-gray-400 mr-1"></i>
                                    <span class="text-xs text-gray-500">Rp{{ number_format($produkKurangLaku->price,0,',','.') }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400">Tidak ada data</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Tabel Transaksi -->
            <div class="section-card">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-pink-50 rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-receipt text-lg text-pink-500"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi</h3>
                                <p class="text-gray-500 text-sm">Riwayat transaksi penjualan pada periode yang dipilih</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="bi bi-calendar3-range mr-2"></i>
                            {{ date('d M Y', strtotime($start)) }} - {{ date('d M Y', strtotime($end)) }}
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left" style="width: 80px;">
                                    <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-hash text-pink-400"></i>
                                        <span>No</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-hash text-pink-400"></i>
                                        <span>No. Penjualan</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-calendar3 text-pink-400"></i>
                                        <span>Tanggal & Waktu</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-credit-card text-pink-400"></i>
                                        <span>Metode Pembayaran</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="bi bi-cash text-pink-400"></i>
                                        <span>Total</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($sales as $index => $sale)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="bg-gray-100 text-gray-700 text-sm font-medium px-2.5 py-0.5 rounded-lg">
                                                {{ $sale->order_number }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $sale->order_time ? \Carbon\Carbon::parse($sale->order_time)->format('d M Y') : '-' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $sale->order_time ? \Carbon\Carbon::parse($sale->order_time)->format('H:i') : '' }} WIB
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badge = match($sale->payment_method) {
                                                'cash' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                'debit' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                                'transfer' => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                                                default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                            };
                                            $icon = match($sale->payment_method) {
                                                'cash' => 'bi-cash-coin',
                                                'debit' => 'bi-credit-card-2-front',
                                                'transfer' => 'bi-bank',
                                                default => 'bi-question-circle',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium {{ $badge }} ring-1 ring-inset">
                                            <i class="bi {{ $icon }} mr-1"></i>
                                            {{ ucfirst($sale->payment_method ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900">
                                            Rp{{ number_format($sale->total,0,',','.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-gray-500 font-medium">Tidak ada data penjualan</p>
                                            <p class="text-sm text-gray-400 mt-1">Coba ubah rentang tanggal pencarian Anda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
