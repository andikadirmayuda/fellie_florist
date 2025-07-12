<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetSize;
use App\Models\BouquetComponent;
use App\Models\Product;
use Illuminate\Http\Request;

class BouquetComponentController extends Controller
{
    public function index()
    {
        $components = BouquetComponent::with(['bouquet', 'size', 'product'])->get();
        return view('bouquet-components.index', compact('components'));
    }

    public function create()
    {
        $bouquets = Bouquet::all();
        $sizes = BouquetSize::all();
        $products = Product::all();
        return view('bouquet-components.create', compact('bouquets', 'sizes', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bouquet_id' => 'required|exists:bouquets,id',
            'size_id' => 'required|exists:bouquet_sizes,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        BouquetComponent::create($validated);

        return redirect()->route('bouquet-components.index')
            ->with('success', 'Komponen buket berhasil ditambahkan.');
    }

    public function edit(BouquetComponent $bouquetComponent)
    {
        $bouquets = Bouquet::all();
        $sizes = BouquetSize::all();
        $products = Product::all();
        return view('bouquet-components.edit', compact('bouquetComponent', 'bouquets', 'sizes', 'products'));
    }

    public function update(Request $request, BouquetComponent $bouquetComponent)
    {
        $validated = $request->validate([
            'bouquet_id' => 'required|exists:bouquets,id',
            'size_id' => 'required|exists:bouquet_sizes,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $bouquetComponent->update($validated);

        return redirect()->route('bouquet-components.index')
            ->with('success', 'Komponen buket berhasil diperbarui.');
    }

    public function destroy(BouquetComponent $bouquetComponent)
    {
        $bouquetComponent->delete();

        return redirect()->route('bouquet-components.index')
            ->with('success', 'Komponen buket berhasil dihapus.');
    }
}
