<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bouquet;
use App\Models\BouquetCategory;

class BouquetCrud extends Component
{
    public $bouquets, $name, $category_id, $description, $bouquet_id, $isEdit = false;
    public $categories = [];

    protected $rules = [
        'name' => 'required|string|max:100',
        'category_id' => 'required|exists:bouquet_categories,id',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadBouquets();
        $this->categories = BouquetCategory::orderBy('name')->get();
    }

    public function loadBouquets()
    {
        $this->bouquets = Bouquet::with('category')->orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->category_id = '';
        $this->description = '';
        $this->bouquet_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();
        Bouquet::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'description' => $this->description,
        ]);
        $this->resetForm();
        $this->loadBouquets();
        session()->flash('success', 'Produk bouquet berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bq = Bouquet::findOrFail($id);
        $this->bouquet_id = $bq->id;
        $this->name = $bq->name;
        $this->category_id = $bq->category_id;
        $this->description = $bq->description;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();
        $bq = Bouquet::findOrFail($this->bouquet_id);
        $bq->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'description' => $this->description,
        ]);
        $this->resetForm();
        $this->loadBouquets();
        session()->flash('success', 'Produk bouquet berhasil diupdate!');
    }

    public function delete($id)
    {
        Bouquet::destroy($id);
        $this->loadBouquets();
        session()->flash('success', 'Produk bouquet berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.bouquet-crud');
    }
}
