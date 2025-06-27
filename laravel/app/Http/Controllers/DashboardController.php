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

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik utama
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalSales = Sale::count();

        // Produk stok menipis
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')->get();

        // Pesanan terbaru
        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        // Data grafik penjualan (7 hari terakhir)
        $sales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $salesChartData = [
            'labels' => $sales->pluck('date')->map(fn($d) => date('d M', strtotime($d)))->toArray(),
            'datasets' => [[
                'label' => 'Penjualan',
                'data' => $sales->pluck('total')->toArray(),
                'backgroundColor' => '#111827',
                'borderColor' => '#111827',
                'fill' => false,
            ]],
        ];

        // Data grafik pesanan (7 hari terakhir)
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $ordersChartData = [
            'labels' => $orders->pluck('date')->map(fn($d) => date('d M', strtotime($d)))->toArray(),
            'datasets' => [[
                'label' => 'Pesanan',
                'data' => $orders->pluck('total')->toArray(),
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
