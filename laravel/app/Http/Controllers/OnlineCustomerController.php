<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicOrder;
use App\Models\Customer;
use App\Models\ResellerCode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OnlineCustomerController extends Controller
{
    /**
     * Display a listing of online customers.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Query untuk mendapatkan pelanggan online dari PublicOrder
        $query = PublicOrder::select(
            'customer_name',
            'wa_number',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(amount_paid) as total_spent'),
            DB::raw('MAX(created_at) as last_order_date'),
            DB::raw('MIN(created_at) as first_order_date')
        )
        ->whereNotNull('customer_name')
        ->whereNotNull('wa_number')
        ->groupBy('customer_name', 'wa_number');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('wa_number', 'LIKE', "%{$search}%");
            });
        }

        $onlineCustomers = $query->orderBy('last_order_date', 'desc')->paginate(15);

        return view('online-customers.index', compact('onlineCustomers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($wa_number)
    {
        // Ambil detail pelanggan berdasarkan nomor WhatsApp
        $customerData = PublicOrder::select(
            'customer_name',
            'wa_number',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(amount_paid) as total_spent'),
            DB::raw('MAX(created_at) as last_order_date'),
            DB::raw('MIN(created_at) as first_order_date')
        )
        ->where('wa_number', $wa_number)
        ->groupBy('customer_name', 'wa_number')
        ->first();

        if (!$customerData) {
            abort(404, 'Pelanggan tidak ditemukan');
        }

        // Ambil data customer jika ada
        $customer = Customer::where('phone', $wa_number)->first();
        $customerData->customer = $customer;

        // Ambil kode reseller aktif dan riwayat (inisialisasi dengan collection kosong jika tidak ada)
        $activeResellerCodes = collect();
        $resellerCodeHistory = collect();
        
        if ($customer && $customer->is_reseller) {
            $activeResellerCodes = ResellerCode::forCustomer($wa_number)->active()->get();
            $resellerCodeHistory = ResellerCode::forCustomer($wa_number)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        // Ambil riwayat pesanan
        $orders = PublicOrder::where('wa_number', $wa_number)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('online-customers.show', compact('customerData', 'orders', 'activeResellerCodes', 'resellerCodeHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($wa_number)
    {
        $customerData = PublicOrder::select(
            'customer_name',
            'wa_number'
        )
        ->where('wa_number', $wa_number)
        ->first();

        if (!$customerData) {
            abort(404, 'Pelanggan tidak ditemukan');
        }

        // Ambil data customer jika ada
        $customer = Customer::where('phone', $wa_number)->first();
        $customerData->customer = $customer;

        return view('online-customers.edit', compact('customerData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $wa_number)
    {
        $request->validate([
            'is_reseller' => 'boolean',
            'reseller_discount' => 'nullable|numeric|min:0|max:100',
            'promo_discount' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Cari atau buat customer record untuk menyimpan data tambahan
        $customer = Customer::firstOrCreate(
            ['phone' => $wa_number],
            [
                'name' => PublicOrder::where('wa_number', $wa_number)->value('customer_name'),
                'email' => null,
                'address' => null,
            ]
        );

        // Update data tambahan
        $customer->update([
            'is_reseller' => $request->boolean('is_reseller'),
            'reseller_discount' => $request->reseller_discount,
            'promo_discount' => $request->promo_discount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('online-customers.show', $wa_number)
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Set customer as reseller
     */
    public function setAsReseller(Request $request, $wa_number)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100'
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $wa_number],
            [
                'name' => PublicOrder::where('wa_number', $wa_number)->value('customer_name'),
                'email' => null,
                'address' => null,
            ]
        );

        $customer->update([
            'is_reseller' => true,
            'reseller_discount' => $request->discount_percentage,
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil ditetapkan sebagai reseller');
    }

    /**
     * Set promo discount for customer
     */
    public function setPromoDiscount(Request $request, $wa_number)
    {
        $request->validate([
            'promo_discount' => 'required|numeric|min:0|max:100'
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $wa_number],
            [
                'name' => PublicOrder::where('wa_number', $wa_number)->value('customer_name'),
                'email' => null,
                'address' => null,
            ]
        );

        $customer->update([
            'promo_discount' => $request->promo_discount,
        ]);

        return redirect()->back()->with('success', 'Discount promo berhasil ditetapkan');
    }

    /**
     * Generate kode reseller baru untuk customer
     */
    public function generateResellerCode(Request $request, $wa_number)
    {
        $request->validate([
            'expiry_hours' => 'required|integer|min:1|max:168', // Maksimal 1 minggu
            'notes' => 'nullable|string|max:500'
        ]);

        // Cek apakah customer terdaftar sebagai reseller
        $customer = Customer::where('phone', $wa_number)->first();
        if (!$customer || !$customer->is_reseller) {
            return redirect()->back()->with('error', 'Customer belum terdaftar sebagai reseller');
        }

        // Pastikan expiry_hours adalah integer
        $expiryHours = (int) $request->expiry_hours;

        // Generate kode baru
        $resellerCode = ResellerCode::createForCustomer(
            $wa_number,
            $expiryHours,
            $request->notes
        );

        return redirect()->back()->with('success', "Kode reseller berhasil dibuat: {$resellerCode->code}");
    }

    /**
     * Revoke/batalkan kode reseller
     */
    public function revokeResellerCode($wa_number, $codeId)
    {
        $resellerCode = ResellerCode::where('id', $codeId)
            ->where('wa_number', $wa_number)
            ->where('is_used', false)
            ->first();

        if (!$resellerCode) {
            return redirect()->back()->with('error', 'Kode reseller tidak ditemukan atau sudah digunakan');
        }

        $resellerCode->update([
            'expires_at' => Carbon::now(), // Set expired sekarang
            'notes' => ($resellerCode->notes ? $resellerCode->notes . ' | ' : '') . 'Revoked by admin at ' . Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Kode reseller berhasil dibatalkan');
    }

    /**
     * API untuk validasi kode reseller dari frontend public
     */
    public function validateResellerCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'wa_number' => 'required|string'
        ]);

        $validation = ResellerCode::validateCode($request->code, $request->wa_number);
        
        if ($validation['valid']) {
            // Ambil data customer untuk mendapatkan discount
            $customer = Customer::where('phone', $request->wa_number)->first();
            $discount = $customer ? $customer->reseller_discount : 0;

            return response()->json([
                'valid' => true,
                'message' => $validation['message'],
                'discount_percentage' => $discount,
                'code_id' => $validation['code']->id
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => $validation['message']
        ], 400);
    }

    /**
     * Mark kode sebagai used (dipanggil saat checkout sukses)
     */
    public function markResellerCodeUsed(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'wa_number' => 'required|string',
            'order_id' => 'nullable|integer'
        ]);

        $resellerCode = ResellerCode::where('code', $request->code)
            ->where('wa_number', $request->wa_number)
            ->where('is_used', false)
            ->first();

        if ($resellerCode && $resellerCode->isValid()) {
            $resellerCode->markAsUsed($request->order_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Kode reseller berhasil digunakan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode reseller tidak valid'
        ], 400);
    }
}
