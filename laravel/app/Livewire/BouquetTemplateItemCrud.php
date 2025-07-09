<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BouquetTemplateItem;
use App\Models\Bouquet;
use App\Models\Product;

class BouquetTemplateItemCrud extends Component
{
    public $items, $bouquet_id, $product_id, $quantity, $item_id, $isEdit = false;
    public $bouquets = [], $products = [];

    protected $rules = [
        'bouquet_id' => 'required|exists:bouquets,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->loadItems();
        $this->bouquets = Bouquet::orderBy('name')->get();
        $this->products = Product::orderBy('name')->get();
    }

    public function loadItems()
    {
        $this->items = BouquetTemplateItem::with(['bouquet', 'product'])->orderBy('bouquet_id')->get();
    }

    public function resetForm()
    {
        $this->bouquet_id = '';
        $this->product_id = '';
        $this->quantity = '';
        $this->item_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();
        BouquetTemplateItem::create([
            'bouquet_id' => $this->bouquet_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ]);
        $this->resetForm();
        $this->loadItems();
        session()->flash('success', 'Item template berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = BouquetTemplateItem::findOrFail($id);
        $this->item_id = $item->id;
        $this->bouquet_id = $item->bouquet_id;
        $this->product_id = $item->product_id;
        $this->quantity = $item->quantity;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();
        $item = BouquetTemplateItem::findOrFail($this->item_id);
        $item->update([
            'bouquet_id' => $this->bouquet_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ]);
        $this->resetForm();
        $this->loadItems();
        session()->flash('success', 'Item template berhasil diupdate!');
    }

    public function delete($id)
    {
        BouquetTemplateItem::destroy($id);
        $this->loadItems();
        session()->flash('success', 'Item template berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.bouquet-template-item-crud');
    }
}
