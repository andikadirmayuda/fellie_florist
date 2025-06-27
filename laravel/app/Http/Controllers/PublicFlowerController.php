<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicFlowerController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang stoknya > 0, tampilkan semua kategori
        $flowers = Product::with(['category', 'prices'])
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        $lastUpdated = Product::max('updated_at');

        return view('public.flowers', [
            'flowers' => $flowers,
            'lastUpdated' => $lastUpdated,
        ]);
    }
}
