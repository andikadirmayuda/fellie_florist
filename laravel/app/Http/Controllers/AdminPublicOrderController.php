<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use App\Models\Product;
use App\Services\WhatsAppNotificationService;
use App\Services\PushNotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminPublicOrderController extends Controller
{
    /**
     * Hapus semua pesanan publik dengan status tertentu.
     */
    public function bulkDelete(Request $request)
    {
        $statuses = $request->input('statuses', []);
        if (!is_array($statuses) || empty($statuses)) {
            return back()->with('error', 'Pilih minimal satu status yang ingin dihapus.');
        }
        $orders = PublicOrder::whereIn('status', $statuses)->get();
        $count = $orders->count();
        if ($count === 0) {
            return back()->with('info', 'Tidak ada pesanan publik dengan status yang dipilih.');
        }

        $deleted = 0;
        foreach ($orders as $order) {
            if (method_exists($order, 'items')) {
                $order->items()->delete();
            }

            // Hapus bukti pembayaran jika ada
            if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Hapus packing photo jika ada
            if ($order->packing_photo && Storage::disk('public')->exists($order->packing_photo)) {
                Storage::disk('public')->delete($order->packing_photo);
            }

            // Hapus file-file packing_files jika ada
            if ($order->packing_files) {
                $packingFiles = json_decode($order->packing_files, true);
                if (is_array($packingFiles)) {
                    foreach ($packingFiles as $filePath) {
                        if ($filePath && Storage::disk('public')->exists($filePath)) {
                            Storage::disk('public')->delete($filePath);
                        }
                    }
                }
            }

            $order->delete();
            $deleted++;
        }
        return back()->with('success', "$deleted pesanan publik dengan status terpilih berhasil dihapus.");
    }
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
        // Debug log incoming request
        Log::info('Update Status Request:', [
            'id' => $id,
            'status' => $request->input('status'),
            'has_packing_files' => $request->hasFile('packing_files'),
            'has_packing_photo' => $request->hasFile('packing_photo'),
            'files_count' => $request->hasFile('packing_files') ? count($request->file('packing_files')) : 0
        ]);

        $order = PublicOrder::with('items')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['pending', 'processed', 'packing', 'ready', 'shipped', 'completed', 'cancelled'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Batasi perubahan status:
        if (in_array($oldStatus, ['completed', 'cancelled'])) {
            return back()->with('error', 'Status pesanan sudah final dan tidak dapat diubah lagi.');
        }
        if ($oldStatus === 'processed' && !in_array($newStatus, ['packing', 'cancelled'])) {
            return back()->with('error', 'Status Diproses hanya bisa diubah ke Dikemas atau Dibatalkan.');
        }
        if ($oldStatus === 'packing' && !in_array($newStatus, ['ready', 'cancelled'])) {
            return back()->with('error', 'Status Dikemas hanya bisa diubah ke Pesanan Sudah Siap atau Dibatalkan.');
        }
        if ($oldStatus === 'ready' && !in_array($newStatus, ['shipped', 'completed', 'cancelled'])) {
            return back()->with('error', 'Status Pesanan Sudah Siap hanya bisa diubah ke Dikirim, Selesai, atau Dibatalkan.');
        }
        if ($oldStatus === 'pending') {
            if ($newStatus === 'processed') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->reduceStock($item->quantity * $item->unit_equivalent, 'public_order', 'public_order:' . $order->id, 'Pesanan publik diproses');
                    }
                }
                $order->stock_holded = true;
            } elseif ($newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->addStock($item->quantity * $item->unit_equivalent, 'public_order_cancel', 'public_order:' . $order->id, 'Pesanan publik dibatalkan');
                    }
                }
                $order->stock_holded = false;
            }
        }

        // Jika status ke packing, wajib upload foto
        if ($newStatus === 'packing') {
            // Support both single file (packing_photo) and multiple files (packing_files[])
            if ($request->hasFile('packing_files')) {
                // Handle multiple files upload
                $uploadedFiles = [];
                $files = $request->file('packing_files');

                foreach ($files as $file) {
                    if (!$file->isValid()) {
                        Log::error('File packing tidak valid: ' . $file->getClientOriginalName());
                        return back()->with('error', 'File upload tidak valid: ' . $file->getClientOriginalName() . '. Coba pilih file lain.');
                    }

                    // Validasi ukuran file (max 10MB)
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return back()->with('error', 'File terlalu besar: ' . $file->getClientOriginalName() . '. Maksimal 10MB per file.');
                    }

                    // Validasi tipe file
                    $allowedMimes = [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                        'image/bmp',
                        'video/mp4',
                        'video/mov',
                        'video/avi',
                        'video/wmv',
                        'video/flv',
                        'video/webm'
                    ];
                    if (!in_array($file->getMimeType(), $allowedMimes)) {
                        return back()->with('error', 'Tipe file tidak didukung: ' . $file->getClientOriginalName() . '. Hanya gambar dan video yang diperbolehkan.');
                    }

                    try {
                        // Simpan file ke storage/app/public/packing_files
                        $path = $file->store('packing_files', 'public');
                        if (!$path) {
                            Log::error('Gagal menyimpan file packing ke storage: ' . $file->getClientOriginalName());
                            return back()->with('error', 'Gagal menyimpan file: ' . $file->getClientOriginalName() . '. Pastikan permission folder storage benar.');
                        }
                        $uploadedFiles[] = $path;
                    } catch (\Throwable $e) {
                        Log::error('Upload packing file gagal: ' . $e->getMessage() . ' - File: ' . $file->getClientOriginalName());
                        return back()->with('error', 'Upload file gagal: ' . $file->getClientOriginalName() . ' - ' . $e->getMessage());
                    }
                }

                // Simpan array files sebagai JSON
                $order->packing_files = json_encode($uploadedFiles);

                // Untuk backward compatibility, simpan file pertama sebagai packing_photo juga
                if (!empty($uploadedFiles)) {
                    $order->packing_photo = $uploadedFiles[0];
                }
            } elseif ($request->hasFile('packing_photo')) {
                // Handle single file upload (legacy support)
                $file = $request->file('packing_photo');
                if (!$file->isValid()) {
                    Log::error('File packing_photo tidak valid.');
                    return back()->with('error', 'File upload tidak valid. Coba pilih file lain.');
                }

                // Validasi ukuran file (max 10MB)
                if ($file->getSize() > 10 * 1024 * 1024) {
                    return back()->with('error', 'File terlalu besar. Maksimal 10MB.');
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
                Log::error('packing_photo atau packing_files tidak ditemukan pada request.');
                return back()->with('error', 'Foto barang wajib diupload saat status Dikemas.');
            }
        }


        $order->status = $newStatus;
        $order->save();

        // Trigger push notification untuk status update
        try {
            PushNotificationService::sendStatusUpdateNotification($order, $oldStatus, $newStatus);
        } catch (\Exception $e) {
            Log::warning('Failed to send push notification for status update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diubah.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = PublicOrder::findOrFail($id);
        $newStatus = $request->input('payment_status');
        $amountPaid = $request->input('amount_paid');
        $allowed = [
            'waiting_confirmation',
            'ready_to_pay',
            'waiting_payment',
            'waiting_verification',
            'dp_paid',
            'partial_paid',
            'paid',
            'rejected',
            'cancelled'
        ];
        if (!in_array($newStatus, $allowed)) {
            return back()->with('error', 'Status pembayaran tidak valid.');
        }

        // Jika status pembayaran ke dp_paid, partial_paid, atau paid, wajib upload bukti pembayaran
        if (in_array($newStatus, ['dp_paid', 'partial_paid', 'paid'])) {
            // Validasi nominal pembayaran (hanya wajib untuk DP dan partial, paid otomatis)
            if (in_array($newStatus, ['dp_paid', 'partial_paid']) && (!$amountPaid || $amountPaid <= 0)) {
                return back()->with('error', 'Nominal pembayaran harus diisi dan lebih dari 0 untuk status DP/Sebagian.');
            }

            // Validasi bukti pembayaran (wajib untuk semua)
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

        // Update amount_paid berdasarkan input admin atau otomatis untuk status paid
        if (in_array($newStatus, ['dp_paid', 'partial_paid'])) {
            // Untuk DP atau pembayaran sebagian, gunakan input dari admin
            $order->amount_paid = $amountPaid;
        } elseif ($newStatus === 'paid') {
            // Untuk status lunas, SELALU set amount_paid = total pesanan
            $totalOrder = $order->items()->sum(DB::raw('quantity * price'));
            $order->amount_paid = $totalOrder;

            // Jika admin input nominal yang berbeda, beri peringatan tapi tetap gunakan total
            if ($amountPaid && $amountPaid != $totalOrder) {
                Log::warning("Admin input amount_paid ($amountPaid) berbeda dengan total order ($totalOrder). Menggunakan total order.");
            }
        }

        $order->save();
        return back()->with('success', 'Status pembayaran berhasil diubah.');
    }



    public function filter(Request $request)
    {
        $query = PublicOrder::query();

        if ($request->filled('nama')) {
            $query->where('customer_name', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('pickup_date', $request->tanggal);
        }
        if ($request->filled('metode')) {
            $query->where('delivery_method', $request->metode);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bayar')) {
            $query->where('payment_status', $request->bayar);
        }

        $orders = $query->orderByDesc('created_at')->paginate(20);

        $rows = '';
        foreach ($orders as $order) {
            $rows .= view('admin.public_orders._order_row', compact('order'))->render();
        }
        $pagination = $orders->links()->toHtml();

        return response()->json([
            'rows' => $rows,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Generate WhatsApp message untuk share info pesanan ke grup karyawan
     */
    public function generateWhatsAppMessage($id)
    {
        try {
            $order = PublicOrder::with('items')->findOrFail($id);
            $message = WhatsAppNotificationService::generateNewOrderMessage($order);

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal generate pesan WhatsApp'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'encoded_message' => urlencode($message),
                'target_info' => WhatsAppNotificationService::getTargetInfo(),
                'whatsapp_url' => WhatsAppNotificationService::generateEmployeeGroupWhatsAppUrl($message)
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat generate pesan WhatsApp'
            ], 500);
        }
    }

    /**
     * Generate pesan WhatsApp untuk share link detail pesanan ke customer
     */
    public function generateCustomerLinkMessage($id)
    {
        try {
            $order = PublicOrder::with('items')->findOrFail($id);
            $message = WhatsAppNotificationService::generateCustomerOrderLinkMessage($order);

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal generate pesan untuk customer'
                ], 500);
            }

            // Format nomor WhatsApp customer
            $customerWhatsApp = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->wa_number));
            $whatsappUrl = WhatsAppNotificationService::generateCustomerWhatsAppUrl($customerWhatsApp, $message);

            return response()->json([
                'success' => true,
                'message' => $message,
                'encoded_message' => urlencode($message),
                'customer_name' => $order->customer_name,
                'customer_whatsapp' => $order->wa_number,
                'whatsapp_url' => $whatsappUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating customer WhatsApp message', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat generate pesan untuk customer'
            ], 500);
        }
    }

    /**
     * Update shipping fee untuk pesanan publik
     */
    public function updateShippingFee(Request $request, $id)
    {
        $order = PublicOrder::findOrFail($id);

        $validated = $request->validate([
            'shipping_fee' => 'required|numeric|min:0',
        ]);

        $order->shipping_fee = $validated['shipping_fee'];
        $order->save();

        return back()->with('success', 'Ongkir berhasil diupdate ke Rp' . number_format($validated['shipping_fee'], 0, ',', '.'));
    }
}
