<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">Laporan Pendapatan</h2>
            <form method="GET" class="flex flex-wrap gap-2 items-end">
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-300">Dari</label>
                    <input type="date" name="start_date" value="{{ $start }}" class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-300">Sampai</label>
                    <input type="date" name="end_date" value="{{ $end }}" class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                </div>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded shadow">Filter</button>
            </form>
        </div>
    </x-slot>
    <div class="flex min-h-screen">
        <div class="flex-1 p-4 md:p-8 bg-white dark:bg-gray-900">
            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="rounded-xl p-5 bg-black text-white border border-gray-800">
                    <div class="text-xs mb-1 text-gray-300">Total Penjualan</div>
                    <div class="text-2xl font-bold">Rp{{ number_format($totalPenjualan,0,',','.') }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Total Pemesanan</div>
                    <div class="text-2xl font-bold">Rp{{ number_format($totalPemesanan,0,',','.') }}</div>
                </div>
                <div class="rounded-xl p-5 bg-white text-black border border-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-700">
                    <div class="text-xs mb-1 text-gray-500 dark:text-gray-300">Total Pendapatan</div>
                    <div class="text-2xl font-bold">Rp{{ number_format($totalPendapatan,0,',','.') }}</div>
                </div>
            </div>
            <!-- Tabel Harian -->
            <h3 class="text-lg font-semibold mb-2">Pendapatan Harian</h3>
            <div class="overflow-x-auto mb-8 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Penjualan</th>
                            <th class="px-4 py-2 text-left">Pemesanan</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($harian as $tgl => $row)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-semibold">{{ $tgl }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['penjualan'],0,',','.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['pemesanan'],0,',','.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['penjualan'] + $row['pemesanan'],0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Tabel Mingguan -->
            <h3 class="text-lg font-semibold mb-2">Pendapatan Mingguan</h3>
            <div class="overflow-x-auto mb-8 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">Minggu Mulai</th>
                            <th class="px-4 py-2 text-left">Penjualan</th>
                            <th class="px-4 py-2 text-left">Pemesanan</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mingguan as $minggu => $row)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-semibold">{{ $minggu }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['penjualan'],0,',','.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['pemesanan'],0,',','.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['penjualan'] + $row['pemesanan'],0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Tabel Bulanan -->
            <h3 class="text-lg font-semibold mb-2">Pendapatan Bulanan</h3>
            <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">Bulan</th>
                            <th class="px-4 py-2 text-left">Penjualan</th>
                            <th class="px-4 py-2 text-left">Pemesanan</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bulanan as $bulan => $row)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-semibold">{{ $bulan }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['penjualan'],0,',','.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['pemesanan'],0,',','.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($row['penjualan'] + $row['pemesanan'],0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
