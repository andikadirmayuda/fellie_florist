<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = OrderHistory::query()
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%");
                });
            })
            ->when($request->date_from, function($query, $date) {
                $query->whereDate('original_created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                $query->whereDate('original_created_at', '<=', $date);
            })
            ->orderBy('original_created_at', 'desc')
            ->paginate(20);

        return view('orders.history.index', compact('histories'));
    }

    public function show(OrderHistory $history)
    {
        return view('orders.history.show', compact('history'));
    }
}
