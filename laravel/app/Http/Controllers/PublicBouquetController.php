<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetCategory;
use App\Models\BouquetSize;
use Illuminate\Http\Request;

class PublicBouquetController extends Controller
{
    public function index()
    {
        // Ambil semua bouquet yang aktif beserta komponennya
        $bouquets = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size'])
            ->orderBy('name')
            ->get();

        // Ambil kategori bouquet untuk filter
        $bouquetCategories = BouquetCategory::orderBy('name')->get();

        // Ambil ukuran bouquet untuk informasi
        $bouquetSizes = BouquetSize::orderBy('name')->get();

        $lastUpdated = Bouquet::max('updated_at') ?? now();

        return view('public.bouquets', [
            'bouquets' => $bouquets,
            'bouquetCategories' => $bouquetCategories,
            'bouquetSizes' => $bouquetSizes,
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

        // Kelompokkan komponen berdasarkan ukuran
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
