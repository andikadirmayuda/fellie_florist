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
        $bouquet = Bouquet::with(['category', 'components.product', 'sizes', 'prices.size'])
            ->findOrFail($id);

        return response()->json($bouquet);
    }
}
