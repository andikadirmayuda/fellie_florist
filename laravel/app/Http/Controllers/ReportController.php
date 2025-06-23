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
            ->selectRaw('SUM(sale_items.qty) as total_terjual')
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
            ->selectRaw('COALESCE(SUM(sale_items.qty),0) as total_terjual')
            ->groupBy('products.id')
            ->orderBy('total_terjual', 'asc')
            ->first();

        return view('reports.sales', compact('sales', 'start', 'end', 'totalPendapatan', 'totalTransaksi', 'produkTerlaris', 'produkKurangLaku'));
    }

    // Laporan Stok
    public function stock(Request $request)
    {
        $products = Product::with('category')->get();
        $logs = InventoryLog::latest()->limit(100)->get();
        return view('reports.stock', compact('products', 'logs'));
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
}
