<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BouquetCategory;

class BouquetCategoryCrud extends Component
{
    public $categories, $name, $category_id, $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:100',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = BouquetCategory::orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->category_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();
        BouquetCategory::create(['name' => $this->name]);
        $this->resetForm();
        $this->loadCategories();
        session()->flash('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $cat = BouquetCategory::findOrFail($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();
        $cat = BouquetCategory::findOrFail($this->category_id);
        $cat->update(['name' => $this->name]);
        $this->resetForm();
        $this->loadCategories();
        session()->flash('success', 'Kategori berhasil diupdate!');
    }

    public function delete($id)
    {
        BouquetCategory::destroy($id);
        $this->loadCategories();
        session()->flash('success', 'Kategori berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.bouquet-category-crud');
    }
}
