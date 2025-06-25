<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Cek stok produk sebelum transaksi
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || !$product->hasEnoughStock($item['quantity'])) {
                return back()->withErrors(['error' => 'Stok produk ' . ($product ? $product->name : 'tidak ditemukan') . ' tidak mencukupi!'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $today = Carbon::now()->format('dmY');
            $count = Sale::whereDate('order_time', Carbon::today())->count() + 1;
            $order_number = 'SALE-' . $today . str_pad($count, 3, '0', STR_PAD_LEFT);

            $subtotal = collect($request->items)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });
            $total = $subtotal; // Bisa ditambah diskon/pajak jika perlu

            // Generate kode unik public_code
            $public_code = bin2hex(random_bytes(8));

            $cash_given = null;
            $change = null;
            if ($request->payment_method === 'cash') {
                $cash_given = $request->input('cash_given') ?? 0;
                $change = $cash_given - $total;
            }
            $sale = Sale::create([
                'order_number' => $order_number,
                'order_time' => now(),
                'total' => $total,
                'subtotal' => $subtotal,
                'payment_method' => $request->payment_method,
                'public_code' => $public_code,
                'cash_given' => $cash_given,
                'change' => $change,
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
                // Update stok produk sesuai tipe harga
                $product = Product::find($item['product_id']);
                $qtyToReduce = $item['quantity'];
                // Cek jika ada unit_equivalent di product_prices
                $price = $product->prices->where('type', $item['price_type'])->first();
                if ($price && $price->unit_equivalent && $price->unit_equivalent > 0) {
                    $qtyToReduce = $item['quantity'] * $price->unit_equivalent;
                }
                $product->decrementStock($qtyToReduce);
                // Catat log inventaris
                \App\Models\InventoryLog::create([
                    'product_id' => $item['product_id'],
                    'qty' => -abs($qtyToReduce),
                    'source' => 'sale',
                    'reference_id' => $sale->id,
                    'notes' => 'Pengurangan stok karena penjualan',
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

    public function downloadPdf($id)
    {
        $sale = \App\Models\Sale::with('items.product')->findOrFail($id);
        $pdf = Pdf::loadView('sales.receipt', compact('sale'));
        $filename = 'Struk-Penjualan-' . $sale->order_number . '.pdf';
        return $pdf->download($filename);
    }
}
