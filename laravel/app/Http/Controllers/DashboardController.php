<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Role;
use App\Models\User;
use App\Models\PublicOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik utama
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalOrders = PublicOrder::count(); // Ubah ke PublicOrder untuk pesanan online
        $totalSales = Sale::count();

        // Produk stok menipis
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')->get();

        // Pesanan terbaru
        $recentOrders = PublicOrder::latest()->take(5)->get();

        // Data grafik penjualan (7 hari terakhir)
        $sales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Buat array 7 hari terakhir untuk memastikan semua tanggal tampil
        $last7DaysSales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');
            $total = $sales->where('date', $date)->first()->total ?? 0;
            
            $last7DaysSales->push([
                'date' => $dateFormatted,
                'total' => $total
            ]);
        }
        
        $salesChartData = [
            'labels' => $last7DaysSales->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Penjualan',
                'data' => $last7DaysSales->pluck('total')->toArray(),
                'backgroundColor' => '#111827',
                'borderColor' => '#111827',
                'fill' => false,
            ]],
        ];

        // Data grafik pesanan (7 hari terakhir)
        // Ambil semua pesanan publik dalam 7 hari terakhir untuk menampilkan performa
        $orders = PublicOrder::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buat array 7 hari terakhir untuk memastikan semua tanggal tampil
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');
            $count = $orders->where('date', $date)->first()->total ?? 0;
            
            $last7Days->push([
                'date' => $dateFormatted,
                'total' => $count
            ]);
        }

        $ordersChartData = [
            'labels' => $last7Days->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Pesanan Publik',
                'data' => $last7Days->pluck('total')->toArray(),
                'backgroundColor' => '#6B7280',
            ]],
        ];

        // Produk ready stock (stok > 0)
        $readyProducts = Product::with(['category', 'prices'])
            ->where('current_stock', '>', 0)
            ->orderByDesc('current_stock')
            ->take(10)
            ->get();

        $data = compact(
            'user',
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'lowStockProducts',
            'recentOrders',
            'salesChartData',
            'ordersChartData',
            'readyProducts' // tambahkan ini
        );

        // Selalu arahkan ke dashboard utama
        return view('dashboard', $data);
    }
}
