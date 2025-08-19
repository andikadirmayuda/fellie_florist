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
use App\Models\InventoryLog;
use App\Models\BouquetCategory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik utama
        // Hitung pelanggan online berdasarkan unique wa_number dari PublicOrder, bukan dari tabel Customer
        $totalCustomers = PublicOrder::select('wa_number')
            ->whereNotNull('customer_name')
            ->whereNotNull('wa_number')
            ->where('wa_number', '!=', '')
            ->where('wa_number', '!=', '-')
            ->distinct()
            ->count();

        $totalProducts = Product::count();
        $totalOrders = PublicOrder::whereIn('status', ['pending', 'confirmed', 'processing', 'ready', 'completed'])->count();
        $totalSales = Sale::count(); // Sales menggunakan SoftDeletes, count() otomatis exclude yang deleted

        // Total pendapatan dari Sales dan PublicOrder 
        $salesRevenue = Sale::sum('total'); // Ambil semua sales yang tidak di-soft delete

        // Hitung pendapatan dari PublicOrder melalui items (quantity * price)
        $ordersRevenue = DB::table('public_orders')
            ->join('public_order_items', 'public_orders.id', '=', 'public_order_items.public_order_id')
            ->whereIn('public_orders.status', ['confirmed', 'processing', 'ready', 'completed'])
            ->sum(DB::raw('public_order_items.quantity * public_order_items.price'));

        $totalRevenue = $salesRevenue + $ordersRevenue;

        // Produk stok menipis
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')->get();

        // Pesanan terbaru
        $recentOrders = PublicOrder::latest()->take(5)->get();

        // Data grafik penjualan (7 hari terakhir) - PERBAIKAN
        $sales = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6))
            // Tidak ada filter status karena Sale menggunakan SoftDeletes
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buat array 7 hari terakhir untuk memastikan semua tanggal tampil
        $last7DaysSales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');
            $saleData = $sales->where('date', $date)->first();
            $count = $saleData->count ?? 0;
            $total = $saleData->total ?? 0;

            $last7DaysSales->push([
                'date' => $dateFormatted,
                'count' => $count,
                'total' => $total
            ]);
        }

        $salesChartData = [
            'labels' => $last7DaysSales->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Transaksi Penjualan',
                'data' => $last7DaysSales->pluck('count')->toArray(),
                'backgroundColor' => '#3B82F6',
                'borderColor' => '#3B82F6',
                'fill' => false,
            ]],
        ];

        // Data grafik pesanan (7 hari terakhir) - PERBAIKAN QUERY
        // Gunakan DB query builder langsung untuk menghindari konflik dengan model accessor
        $ordersQuery = DB::table('public_orders')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as order_count')
            ->where('created_at', '>=', now()->subDays(6))
            ->whereIn('status', ['pending', 'confirmed', 'processing', 'ready', 'completed'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Hitung revenue terpisah dari items
        $revenueQuery = DB::table('public_orders')
            ->join('public_order_items', 'public_orders.id', '=', 'public_order_items.public_order_id')
            ->selectRaw('DATE(public_orders.created_at) as date, SUM(public_order_items.quantity * public_order_items.price) as revenue')
            ->where('public_orders.created_at', '>=', now()->subDays(6))
            ->whereIn('public_orders.status', ['pending', 'confirmed', 'processing', 'ready', 'completed'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buat array 7 hari terakhir untuk memastikan semua tanggal tampil
        $last7DaysOrders = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateFormatted = now()->subDays($i)->format('d M');

            // Ambil data count dari ordersQuery (gunakan order_count, bukan total)
            $orderData = $ordersQuery->where('date', $date)->first();
            $count = $orderData->order_count ?? 0;

            // Ambil data revenue dari revenueQuery  
            $revenueData = $revenueQuery->where('date', $date)->first();
            $revenue = $revenueData->revenue ?? 0;

            $last7DaysOrders->push([
                'date' => $dateFormatted,
                'count' => $count,
                'revenue' => $revenue
            ]);
        }

        $ordersChartData = [
            'labels' => $last7DaysOrders->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Pesanan Online',
                'data' => $last7DaysOrders->pluck('count')->toArray(),
                'backgroundColor' => '#8B5CF6',
            ]],
        ];

        // Data grafik pendapatan (7 hari terakhir) - TAMBAHAN BARU
        // Gabungkan pendapatan dari Sales dan PublicOrder
        $revenueChartData = [
            'labels' => $last7DaysOrders->pluck('date')->toArray(),
            'datasets' => [[
                'label' => 'Pendapatan Harian',
                'data' => $last7DaysOrders->map(function ($orderDay) use ($last7DaysSales) {
                    $salesRevenue = $last7DaysSales->where('date', $orderDay['date'])->first()['total'] ?? 0;
                    return $salesRevenue + $orderDay['revenue'];
                })->toArray(),
                'backgroundColor' => '#10B981',
                'borderColor' => '#10B981',
                'fill' => false,
            ]],
        ];

        // Produk ready stock (stok > 0)
        $readyProducts = Product::with(['category', 'prices'])
            ->where('current_stock', '>', 0)
            ->orderByDesc('current_stock')
            ->take(10)
            ->get();

        // Data untuk Performa Produk (berdasarkan kategori)
        $productPerformance = Product::select('categories.name as category_name', DB::raw('SUM(products.current_stock) as total_stock'))
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.current_stock', '>', 0)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_stock')
            ->take(5)
            ->get();

        $productChartData = [
            'labels' => $productPerformance->pluck('category_name')->toArray(),
            'data' => $productPerformance->pluck('total_stock')->toArray()
        ];

        // Data untuk Performa Bouquet - Berdasarkan pesanan publik (public_order_items) yang merupakan produk bouquet
        $bouquetCategorySales = DB::table('public_order_items')
            ->join('products', 'public_order_items.product_id', '=', 'products.id')
            ->join('bouquet_categories', 'products.category_id', '=', 'bouquet_categories.id')
            ->join('public_orders', 'public_order_items.public_order_id', '=', 'public_orders.id')
            ->whereIn('public_orders.status', ['confirmed', 'processing', 'ready', 'completed'])
            ->selectRaw('bouquet_categories.name as category_name, SUM(public_order_items.quantity) as total_sold')
            ->groupBy('bouquet_categories.id', 'bouquet_categories.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Jika tidak ada data penjualan bouquet, fallback ke semua kategori yang tersedia
        if ($bouquetCategorySales->isEmpty()) {
            $allCategories = BouquetCategory::with('bouquets')->get();
            $bouquetChartData = [
                'labels' => $allCategories->pluck('name')->take(5)->toArray(),
                'data' => $allCategories->map(function ($category) {
                    return $category->bouquets->count(); // Jumlah bouquet per kategori
                })->take(5)->toArray()
            ];
        } else {
            $bouquetChartData = [
                'labels' => $bouquetCategorySales->pluck('category_name')->toArray(),
                'data' => $bouquetCategorySales->pluck('total_sold')->toArray()
            ];
        }

        // Jika masih kosong, buat data dummy
        if (empty($bouquetChartData['labels'])) {
            $bouquetChartData = [
                'labels' => ['Wedding', 'Balloon Box', 'Anniversary', 'Birthday', 'Valentine'],
                'data' => [25, 18, 15, 12, 8]
            ];
        }

        // Debug: Uncomment untuk debugging
        // dd([
        //     'bouquet_category_sales' => $bouquetCategorySales,
        //     'bouquet_chart_data' => $bouquetChartData,
        //     'all_categories' => BouquetCategory::with('bouquets')->get(),
        //     'bouquet_order_items_sample' => BouquetOrderItem::with(['bouquet.category'])->take(5)->get()
        // ]);

        $data = compact(
            'user',
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'totalRevenue',
            'lowStockProducts',
            'recentOrders',
            'salesChartData',
            'ordersChartData',
            'revenueChartData',
            'readyProducts',
            'productChartData',
            'bouquetChartData'
        );

        // Selalu arahkan ke dashboard utama
        return view('dashboard', $data);
    }
}
