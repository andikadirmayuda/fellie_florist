<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BouquetOrder;

class BouquetOrderController extends Controller
{
    public function index()
    {
        // Ambil daftar pesanan buket dengan paginasi
        $orders = BouquetOrder::orderByDesc('created_at')->paginate(15);
        return view('bouquet.orders.index', compact('orders'));
    }

    public function create()
    {
        // Tampilkan form tambah pesanan buket
        return view('bouquet.orders.create');
    }

    public function store(Request $request)
    {
        // Simpan pesanan buket baru
        // ...
        return redirect()->route('bouquet.orders.index');
    }

    public function show($id)
    {
        // Tampilkan detail pesanan buket
        return view('bouquet.orders.show', compact('id'));
    }

    public function edit($id)
    {
        // Tampilkan form edit pesanan buket
        return view('bouquet.orders.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update pesanan buket
        // ...
        return redirect()->route('bouquet.orders.index');
    }

    public function destroy($id)
    {
        // Hapus pesanan buket
        // ...
        return redirect()->route('bouquet.orders.index');
    }
}
