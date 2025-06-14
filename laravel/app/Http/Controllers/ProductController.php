<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('code')
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $priceTypes = ProductRequest::getPriceTypes();
        $defaultUnitEquivalents = collect($priceTypes)->mapWithKeys(function ($type) {
            return [$type => ProductRequest::getDefaultUnitEquivalent($type)];
        });

        return view('products.form', compact('categories', 'priceTypes', 'defaultUnitEquivalents'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::create($request->except('prices'));

            // Process prices
            foreach ($request->prices as $priceData) {
                if (!empty($priceData['price'])) {
                    $product->prices()->create($priceData);
                }
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'prices']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $priceTypes = ProductRequest::getPriceTypes();
        $defaultUnitEquivalents = collect($priceTypes)->mapWithKeys(function ($type) {
            return [$type => ProductRequest::getDefaultUnitEquivalent($type)];
        });

        // Prepare existing prices
        $existingPrices = $product->prices->keyBy('type');
        
        return view('products.form', compact('product', 'categories', 'priceTypes', 'defaultUnitEquivalents', 'existingPrices'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->update($request->except('prices'));

            // Delete existing prices
            $product->prices()->delete();

            // Create new prices
            foreach ($request->prices as $priceData) {
                if (!empty($priceData['price'])) {
                    $product->prices()->create($priceData);
                }
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete(); // Using soft delete
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
