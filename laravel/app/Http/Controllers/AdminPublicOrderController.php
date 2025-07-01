<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use Illuminate\Http\Request;

class AdminPublicOrderController extends Controller
{
    public function index()
    {
        $orders = PublicOrder::orderByDesc('created_at')->paginate(20);
        return view('admin.public_orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = PublicOrder::with('items')->findOrFail($id);
        return view('admin.public_orders.show', compact('order'));
    }
}
