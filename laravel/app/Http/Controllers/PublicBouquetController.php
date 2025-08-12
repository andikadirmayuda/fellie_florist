<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetCategory;
use App\Models\BouquetSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicBouquetController extends Controller
{
    public function index(Request $request)
    {
        $query = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size']);

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('prices', function ($priceQuery) use ($request) {
                if ($request->filled('min_price')) {
                    $priceQuery->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $priceQuery->where('price', '<=', $request->max_price);
                }
            });
        }

        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $bouquets = $query->orderBy('name')->get();

        // Ambil kategori bouquet untuk filter
        $bouquetCategories = BouquetCategory::orderBy('name')->get();

        // Ambil ukuran bouquet untuk informasi
        $bouquetSizes = BouquetSize::orderBy('name')->get();

        // Ambil rentang harga untuk filter
        $minPrice = DB::table('bouquet_prices')->min('price') ?? 0;
        $maxPrice = DB::table('bouquet_prices')->max('price') ?? 1000000;

        $lastUpdated = Bouquet::max('updated_at') ?? now();

        return view('public.bouquets', [
            'bouquets' => $bouquets,
            'bouquetCategories' => $bouquetCategories,
            'bouquetSizes' => $bouquetSizes,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'lastUpdated' => $lastUpdated,
            'activeTab' => 'bouquets'
        ]);
    }

    public function getBouquetData()
    {
        // Method untuk mendapatkan data bouquet yang bisa dipanggil dari controller lain
        $bouquets = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size'])
            ->orderBy('name')
            ->get();

        $bouquetCategories = BouquetCategory::orderBy('name')->get();
        $bouquetSizes = BouquetSize::orderBy('name')->get();

        return [
            'bouquets' => $bouquets,
            'bouquetCategories' => $bouquetCategories,
            'bouquetSizes' => $bouquetSizes,
        ];
    }

    public function detail($id)
    {
        $bouquet = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size'])
            ->findOrFail($id);

        return view('public.bouquet.detail', [
            'bouquet' => $bouquet
        ]);
    }

    public function detailJson($id)
    {
        $bouquet = Bouquet::with([
            'category',
            'components.product',
            'components.size',
            'sizes',
            'prices.size'
        ])->findOrFail($id);

        // Bersihkan komponen yang tidak valid (produk sudah dihapus)
        $bouquet->cleanupInvalidComponents();

        // Ambil ulang data bouquet dengan komponen yang valid saja
        $bouquet = Bouquet::with([
            'category',
            'validComponents.product',
            'validComponents.size',
            'sizes',
            'prices.size'
        ])->findOrFail($id);

        // Kelompokkan komponen berdasarkan ukuran - hanya ukuran yang memiliki komponen
        $componentsBySize = $bouquet->validComponents->groupBy('size_id');

        // Format data untuk response
        $bouquetData = $bouquet->toArray();

        // Override components dengan validComponents
        $bouquetData['components'] = $bouquet->validComponents->toArray();
        $bouquetData['components_by_size'] = [];

        foreach ($componentsBySize as $sizeId => $components) {
            // Pastikan sizeId dikonversi ke string untuk konsistensi dengan JavaScript
            $bouquetData['components_by_size'][(string)$sizeId] = $components->toArray();
        }

        return response()->json($bouquetData);
    }
}
