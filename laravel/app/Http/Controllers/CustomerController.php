<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Filter by type if specified
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        // Search by name, email, or phone
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);
        $customerTypes = ['walk-in', 'reseller', 'regular'];

        return view('customers.index', compact('customers', 'customerTypes'));
    }

    public function create()
    {
        $customerTypes = ['walk-in', 'reseller', 'regular'];
        return view('customers.create', compact('customerTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'required|string|max:20|unique:customers',
            'type' => 'required|in:walk-in,reseller,regular',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $customerTypes = ['walk-in', 'reseller', 'regular'];
        return view('customers.edit', compact('customer', 'customerTypes'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'type' => 'required|in:walk-in,reseller,regular',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }

    public function restore($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->forceDelete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus permanen.');
    }

    public function trashed()
    {
        $customers = Customer::onlyTrashed()->latest()->paginate(10);
        return view('customers.trashed', compact('customers'));
    }
}
