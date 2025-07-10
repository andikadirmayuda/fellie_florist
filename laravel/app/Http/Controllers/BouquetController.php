<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use App\Models\BouquetCategory;
use App\Models\BouquetSize;
use App\Models\BouquetPrice;
use App\Models\BouquetComponent;
use Illuminate\Http\Request;

class BouquetController extends Controller
{
    public function create()
    {
        $categories = BouquetCategory::all();
        $sizes = BouquetSize::all();
        return response()->json([
            'categories' => $categories,
            'sizes' => $sizes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:bouquet_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'prices' => 'required|array',
            'prices.*.size_id' => 'required|exists:bouquet_sizes,id',
            'prices.*.price' => 'required|numeric',
            'components' => 'required|array',
            'components.*.product_id' => 'required|exists:products,id',
            'components.*.quantity' => 'required|integer|min:1',
        ]);

        $bouquet = Bouquet::create($validated);

        foreach ($validated['prices'] as $price) {
            $bouquet->prices()->create($price);
        }

        foreach ($validated['components'] as $component) {
            $bouquet->components()->create($component);
        }

        return response()->json($bouquet->load(['category', 'prices', 'components']), 201);
    }

    public function update(Request $request, $id)
    {
        $bouquet = Bouquet::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:bouquet_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'prices' => 'sometimes|array',
            'prices.*.size_id' => 'required_with:prices|exists:bouquet_sizes,id',
            'prices.*.price' => 'required_with:prices|numeric',
            'components' => 'sometimes|array',
            'components.*.product_id' => 'required_with:components|exists:products,id',
            'components.*.quantity' => 'required_with:components|integer|min:1',
        ]);

        $bouquet->update($validated);

        if (isset($validated['prices'])) {
            $bouquet->prices()->delete();
            foreach ($validated['prices'] as $price) {
                $bouquet->prices()->create($price);
            }
        }

        if (isset($validated['components'])) {
            $bouquet->components()->delete();
            foreach ($validated['components'] as $component) {
                $bouquet->components()->create($component);
            }
        }

        return response()->json($bouquet->load(['category', 'prices', 'components']));
    }

    public function destroy($id)
    {
        $bouquet = Bouquet::findOrFail($id);
        $bouquet->prices()->delete();
        $bouquet->components()->delete();
        $bouquet->delete();
        return response()->json(['message' => 'Bouquet deleted successfully']);
    }
    public function index()
    {
        $bouquets = Bouquet::with('category')->paginate(15);
        return view('bouquets.index', compact('bouquets'));
    }

    public function show($id)
    {
        $bouquet = Bouquet::with(['category', 'prices', 'components.product'])->findOrFail($id);
        return response()->json($bouquet);
    }

    // Tambahkan method create, store, update, destroy sesuai kebutuhan aplikasi Anda
}
