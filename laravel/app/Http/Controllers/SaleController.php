<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $categories = DB::table('categories')->get();
        $products = Product::with(['prices'])->get();
        return view('sales.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        // Konversi items dari JSON ke array jika perlu
        if ($request->has('items') && is_string($request->items)) {
            $request->merge(['items' => json_decode($request->items, true) ?: []]);
        }
        $request->validate([
            'items' => 'required|array',
            'payment_method' => 'required|in:cash,debit,transfer',
        ]);

        DB::beginTransaction();
        try {
            $today = Carbon::now()->format('dmY');
            $count = Sale::whereDate('order_time', Carbon::today())->count() + 1;
            $order_number = 'SALE-' . $today . str_pad($count, 3, '0', STR_PAD_LEFT);

            $subtotal = collect($request->items)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });
            $total = $subtotal; // Bisa ditambah diskon/pajak jika perlu

            $sale = Sale::create([
                'order_number' => $order_number,
                'order_time' => now(),
                'total' => $total,
                'subtotal' => $subtotal,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'price_type' => $item['price_type'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);
        if (request('print') == 1) {
            return view('sales.receipt', compact('sale'));
        }
        return view('sales.show', compact('sale'));
    }
}
