<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BouquetSaleController extends Controller
{
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('bouquet.sales', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flowers' => 'required|array',
            'flowers.*.product_id' => 'required|exists:products,id',
            'flowers.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->flowers as $flower) {
                $product = Product::find($flower['product_id']);
                if ($product->stock < $flower['quantity']) {
                    return back()->withErrors(['msg' => "Stok bunga {$product->name} tidak cukup!"])->withInput();
                }
            }
            // Kurangi stok
            foreach ($request->flowers as $flower) {
                $product = Product::find($flower['product_id']);
                $product->stock -= $flower['quantity'];
                $product->save();
            }
            // Simpan data penjualan buket (bisa dikembangkan sesuai kebutuhan)
            DB::commit();
            return back()->with('success', 'Penjualan buket berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan.'])->withInput();
        }
    }
}
