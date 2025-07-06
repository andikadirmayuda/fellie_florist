<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use Illuminate\Support\Facades\Log;
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

    public function updateStatus(Request $request, $id)
    {
        $order = \App\Models\PublicOrder::with('items')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        if (!in_array($newStatus, ['pending', 'processed', 'packing', 'shipped', 'completed', 'cancelled'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Batasi perubahan status:
        if (in_array($oldStatus, ['completed', 'cancelled'])) {
            return back()->with('error', 'Status pesanan sudah final dan tidak dapat diubah lagi.');
        }
        if ($oldStatus === 'processed' && $newStatus !== 'packing') {
            return back()->with('error', 'Status Diproses hanya bisa diubah ke Dikemas.');
        }
        if ($oldStatus === 'pending') {
            if ($newStatus === 'processed') {
                foreach ($order->items as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->reduceStock($item->quantity * $item->unit_equivalent, 'public_order', 'public_order:' . $order->id, 'Pesanan publik diproses');
                    }
                }
                $order->stock_holded = true;
            } elseif ($newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->addStock($item->quantity * $item->unit_equivalent, 'public_order_cancel', 'public_order:' . $order->id, 'Pesanan publik dibatalkan');
                    }
                }
                $order->stock_holded = false;
            }
        }

        // Jika status ke packing, wajib upload foto
        if ($newStatus === 'packing') {
            if ($request->hasFile('packing_photo')) {
                $file = $request->file('packing_photo');
                if (!$file->isValid()) {
                    Log::error('File packing_photo tidak valid.');
                    return back()->with('error', 'File upload tidak valid. Coba pilih file lain.');
                }
                try {
                    // Simpan file ke storage/app/public/packing_photos
                    $path = $file->store('packing_photos', 'public');
                    if (!$path) {
                        Log::error('Gagal menyimpan file packing_photo ke storage.');
                        return back()->with('error', 'Gagal menyimpan file. Pastikan permission folder storage benar.');
                    }
                } catch (\Throwable $e) {
                    Log::error('Upload packing_photo gagal: ' . $e->getMessage());
                    return back()->with('error', 'Upload foto gagal: ' . $e->getMessage());
                }
                // Simpan path relatif untuk ditampilkan dengan asset('storage/...')
                $order->packing_photo = $path;
            } else {
                Log::error('packing_photo tidak ditemukan pada request.');
                return back()->with('error', 'Foto barang wajib diupload saat status Dikemas.');
            }
        }
        

        $order->status = $newStatus;
        $order->save();
        return back()->with('success', 'Status pesanan berhasil diubah.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = \App\Models\PublicOrder::findOrFail($id);
        $newStatus = $request->input('payment_status');
        $amountPaid = $request->input('amount_paid');
        $allowed = [
            'waiting_confirmation', 'ready_to_pay', 'waiting_payment', 'waiting_verification', 'dp_paid', 'partial_paid', 'paid', 'rejected', 'cancelled'
        ];
        if (!in_array($newStatus, $allowed)) {
            return back()->with('error', 'Status pembayaran tidak valid.');
        }

        // Jika status pembayaran ke dp_paid, partial_paid, atau paid, wajib upload bukti pembayaran
        if (in_array($newStatus, ['dp_paid', 'partial_paid', 'paid'])) {
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                if (!$file->isValid()) {
                    Log::error('File payment_proof tidak valid.');
                    return back()->with('error', 'File upload tidak valid. Coba pilih file lain.');
                }
                try {
                    // Simpan file ke storage/app/public/payment_proofs
                    $path = $file->store('payment_proofs', 'public');
                    if (!$path) {
                        Log::error('Gagal menyimpan file payment_proof ke storage.');
                        return back()->with('error', 'Gagal menyimpan file. Pastikan permission folder storage benar.');
                    }
                } catch (\Throwable $e) {
                    Log::error('Upload payment_proof gagal: ' . $e->getMessage());
                    return back()->with('error', 'Upload bukti pembayaran gagal: ' . $e->getMessage());
                }
                $order->payment_proof = $path;
            } else {
                Log::error('payment_proof tidak ditemukan pada request.');
                return back()->with('error', 'Bukti pembayaran wajib diupload untuk status ini.');
            }
        } else {
            // Jika status bukan dp_paid/partial_paid/paid, hapus payment_proof jika ada
            $order->payment_proof = null;
        }

        $order->payment_status = $newStatus;
        if ($amountPaid !== null) {
            $order->amount_paid = $amountPaid;
        }
        $order->save();
        return back()->with('success', 'Status pembayaran berhasil diubah.');
    }


}
