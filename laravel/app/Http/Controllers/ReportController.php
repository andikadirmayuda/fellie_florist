<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\InventoryLog;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facades\Pdf;

class ReportController extends Controller
{
    // Laporan Penjualan
    public function sales(Request $request)
    {
        $start = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $sales = Sale::with('items.product')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalPendapatan = $sales->sum('total');
        $totalTransaksi = $sales->count();

        // Produk terlaris dan terendah
        $produkTerlaris = \App\Models\Product::select('products.*')
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.created_at', [$start, $end])
            ->selectRaw('SUM(sale_items.quantity) as total_terjual')
            ->groupBy('products.id')
            ->orderByDesc('total_terjual')
            ->first();

        $produkKurangLaku = \App\Models\Product::select('products.*')
            ->leftJoin('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where(function($q) use ($start, $end) {
                $q->whereNull('sales.created_at')
                  ->orWhereBetween('sales.created_at', [$start, $end]);
            })
            ->selectRaw('COALESCE(SUM(sale_items.quantity),0) as total_terjual')
            ->groupBy('products.id')
            ->orderBy('total_terjual', 'asc')
            ->first();

        return view('reports.sales', compact('sales', 'start', 'end', 'totalPendapatan', 'totalTransaksi', 'produkTerlaris', 'produkKurangLaku'));
    }

    // Laporan Stok Terintegrasi
    public function stock(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());

        $products = Product::with('category')->get();
        $logs = InventoryLog::whereBetween('created_at', [$start, $end])->latest()->limit(100)->get();

        // Rekap stok masuk, keluar, penyesuaian, dan total per produk
        $rekap = [];
        foreach ($products as $product) {
            $masuk = InventoryLog::where('product_id', $product->id)
                ->where('qty', '>', 0)
                ->whereBetween('created_at', [$start, $end])
                ->sum('qty');
            $keluar = InventoryLog::where('product_id', $product->id)
                ->where('qty', '<', 0)
                ->whereBetween('created_at', [$start, $end])
                ->sum('qty');
            $penyesuaian = InventoryLog::where('product_id', $product->id)
                ->where('source', 'adjustment')
                ->whereBetween('created_at', [$start, $end])
                ->sum('qty');
            $rekap[$product->id] = [
                'masuk' => $masuk,
                'keluar' => abs($keluar),
                'penyesuaian' => $penyesuaian,
                'stok_akhir' => $product->current_stock,
            ];
        }

        return view('reports.stock', compact('products', 'logs', 'rekap', 'start', 'end'));
    }

    // Ekspor laporan penjualan ke PDF
    public function salesPdf(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());
        $sales = \App\Models\Sale::with('items.product')
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $pdf = Pdf::loadView('reports.sales_pdf', compact('sales', 'start', 'end'));
        return $pdf->download('laporan_penjualan.pdf');
    }

    // Laporan Pemesanan
    public function orders(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());

        $orders = \App\Models\Order::with(['customer'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $totalOrder = $orders->count();
        $totalNominal = $orders->sum(function($order) {
            return $order->total + $order->delivery_fee;
        });
        $totalLunas = $orders->where('status', 'completed')->count();
        $totalBelumLunas = $orders->where('status', '!=', 'completed')->count();

        return view('reports.orders', compact('orders', 'start', 'end', 'totalOrder', 'totalNominal', 'totalLunas', 'totalBelumLunas'));
    }

    // Laporan Pelanggan
    public function customers(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());

        $customers = \App\Models\Customer::withCount(['orders' => function($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end]);
        }])->with(['orders' => function($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end]);
        }])->get();

        $totalCustomer = $customers->count();
        $totalOrder = $customers->sum('orders_count');
        $topCustomer = $customers->sortByDesc(function($c) { return $c->orders->sum('total'); })->first();

        return view('reports.customers', compact('customers', 'start', 'end', 'totalCustomer', 'totalOrder', 'topCustomer'));
    }

    // Laporan Pendapatan
    public function income(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Total pendapatan dari penjualan
        $totalPenjualan = \App\Models\Sale::whereBetween('created_at', [$start, $end])->sum('total');
        // Total pendapatan dari pemesanan (order + ongkir)
        $totalPemesanan = \App\Models\Order::whereBetween('created_at', [$start, $end])->sum(\DB::raw('total + delivery_fee'));
        // Total pendapatan gabungan
        $totalPendapatan = $totalPenjualan + $totalPemesanan;

        // Pendapatan harian
        $harian = [];
        foreach (range(0, now()->parse($end)->diffInDays(now()->parse($start))) as $i) {
            $date = now()->parse($start)->copy()->addDays($i)->toDateString();
            $harian[$date] = [
                'penjualan' => \App\Models\Sale::whereDate('created_at', $date)->sum('total'),
                'pemesanan' => \App\Models\Order::whereDate('created_at', $date)->sum(\DB::raw('total + delivery_fee')),
            ];
        }
        // Pendapatan mingguan
        $mingguan = [];
        $startWeek = now()->parse($start)->startOfWeek();
        $endWeek = now()->parse($end)->endOfWeek();
        for ($date = $startWeek->copy(); $date <= $endWeek; $date->addWeek()) {
            $weekStart = $date->copy();
            $weekEnd = $date->copy()->endOfWeek();
            $mingguan[$weekStart->format('Y-m-d')] = [
                'penjualan' => \App\Models\Sale::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total'),
                'pemesanan' => \App\Models\Order::whereBetween('created_at', [$weekStart, $weekEnd])->sum(\DB::raw('total + delivery_fee')),
            ];
        }
        // Pendapatan bulanan
        $bulanan = [];
        $startMonth = now()->parse($start)->startOfYear();
        $endMonth = now()->parse($end)->endOfYear();
        for ($date = $startMonth->copy(); $date <= $endMonth; $date->addMonth()) {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            $bulanan[$monthStart->format('Y-m')] = [
                'penjualan' => \App\Models\Sale::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total'),
                'pemesanan' => \App\Models\Order::whereBetween('created_at', [$monthStart, $monthEnd])->sum(\DB::raw('total + delivery_fee')),
            ];
        }

        return view('reports.income', compact('start', 'end', 'totalPenjualan', 'totalPemesanan', 'totalPendapatan', 'harian', 'mingguan', 'bulanan'));
    }
}
