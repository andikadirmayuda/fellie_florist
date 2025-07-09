<?php

namespace App\Livewire;

use Livewire\Component;


use App\Models\Bouquet;
use App\Models\BouquetCategory;
use App\Models\BouquetSize;
use App\Models\BouquetPrice;
use App\Models\BouquetTemplateItem;
use App\Models\Product;
use App\Models\BouquetOrder;
use App\Models\BouquetOrderItem;
use Illuminate\Support\Carbon;

class OrderBouquetForm extends Component
{
    // Step form
    public $step = 1;

    // Step 1: Data pemesan & pengiriman
    public $customer_name, $receiver_name, $pickup_datetime, $delivery_method, $delivery_address, $greeting_card;

    // Step 2: Pilihan bouquet
    public $category_id, $bouquet_id, $size_id, $discount = 0;
    public $bouquetDescription = '';
    public $bouquetItems = [];
    public $bouquetPrice = 0;
    public $subtotal = 0;
    public $total = 0;

    // Data master
    public $categories = [];
    public $bouquets = [];
    public $sizes = [];

    public function mount()
    {
        $this->categories = BouquetCategory::all();
        $this->sizes = BouquetSize::all();
    }

    public function updatedCategoryId($value)
    {
        $this->bouquets = Bouquet::where('category_id', $value)->get();
        $this->bouquet_id = null;
        $this->resetBouquetDetails();
    }

    public function updatedBouquetId($value)
    {
        $bouquet = Bouquet::with(['templateItems.product', 'prices'])->find($value);
        if ($bouquet) {
            $this->bouquetDescription = $bouquet->description;
            $this->bouquetItems = $bouquet->templateItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? '-',
                    'quantity' => $item->quantity,
                    'stock' => $item->product->current_stock ?? 0,
                ];
            })->toArray();
        } else {
            $this->resetBouquetDetails();
        }
        $this->size_id = null;
        $this->bouquetPrice = 0;
        $this->subtotal = 0;
        $this->total = 0;
    }

    public function updatedSizeId($value)
    {
        if ($this->bouquet_id && $value) {
            $price = BouquetPrice::where('bouquet_id', $this->bouquet_id)->where('size_id', $value)->first();
            $this->bouquetPrice = $price ? $price->price : 0;
            $this->hitungSubtotal();
        }
    }

    public function updatedBouquetItems()
    {
        $this->hitungSubtotal();
    }

    public function updatedDiscount($value)
    {
        $this->hitungSubtotal();
    }

    public function hitungSubtotal()
    {
        $subtotal = $this->bouquetPrice;
        foreach ($this->bouquetItems as $item) {
            // Jika ingin custom harga per item, tambahkan di sini
        }
        $this->subtotal = $subtotal;
        $this->total = max(0, $subtotal - (float)$this->discount);
    }

    public function nextStep()
    {
        $this->validateStep();
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function validateStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'customer_name' => 'required',
                'receiver_name' => 'required',
                'pickup_datetime' => 'required|date',
                'delivery_method' => 'required',
            ]);
        } elseif ($this->step == 2) {
            $this->validate([
                'category_id' => 'required',
                'bouquet_id' => 'required',
                'size_id' => 'required',
            ]);
        }
    }

    public function submitOrder()
    {
        $this->validateStep();
        // Simpan order
        $order = BouquetOrder::create([
            'customer_name' => $this->customer_name,
            'receiver_name' => $this->receiver_name,
            'pickup_at' => $this->pickup_datetime,
            'delivery_method' => $this->delivery_method,
            'delivery_note' => $this->delivery_address,
            'notes' => $this->greeting_card,
            'total_price' => $this->total,
        ]);
        // Simpan item
        foreach ($this->bouquetItems as $item) {
            BouquetOrderItem::create([
                'bouquet_order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => 0, // Jika ingin simpan harga per item, tambahkan logic
            ]);
        }
        session()->flash('success', 'Pemesanan berhasil disimpan!');
        return redirect()->route('orders.index'); // Ganti dengan route yang sesuai
    }

    public function resetBouquetDetails()
    {
        $this->bouquetDescription = '';
        $this->bouquetItems = [];
        $this->bouquetPrice = 0;
        $this->subtotal = 0;
        $this->total = 0;
    }

    public function render()
    {
        return view('livewire.order-bouquet-form', [
            'categories' => $this->categories,
            'bouquets' => $this->bouquets,
            'sizes' => $this->sizes,
        ]);
    }
}
