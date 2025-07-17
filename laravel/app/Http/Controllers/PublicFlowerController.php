<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Bouquet;
use App\Models\BouquetSize;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicFlowerController extends Controller
{
    public function index()
    {
        // Ambil semua produk bunga yang stoknya > 0
        $flowers = Product::with(['category', 'prices'])
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        // Ambil semua bouquet yang aktif beserta komponennya
        $bouquets = Bouquet::with(['category', 'components.product', 'sizes', 'prices'])
            ->orderBy('name')
            ->get();

        // Ambil ukuran bouquet untuk filter
        $bouquetSizes = BouquetSize::orderBy('name')->get();

        $lastUpdated = max(
            Product::max('updated_at') ?? now(),
            Bouquet::max('updated_at') ?? now()
        );

        return view('public.flowers', [
            'flowers' => $flowers,
            'bouquets' => $bouquets,
            'bouquetSizes' => $bouquetSizes,
            'lastUpdated' => $lastUpdated,
            'activeTab' => request()->query('tab', 'flowers') // Default ke tab flowers
        ]);
    }
}
