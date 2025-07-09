<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BouquetSize;

class BouquetSizeCrud extends Component
{
    public $sizes, $name, $size_id, $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:50',
    ];

    public function mount()
    {
        $this->loadSizes();
    }

    public function loadSizes()
    {
        $this->sizes = BouquetSize::orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->size_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();
        BouquetSize::create(['name' => $this->name]);
        $this->resetForm();
        $this->loadSizes();
        session()->flash('success', 'Ukuran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sz = BouquetSize::findOrFail($id);
        $this->size_id = $sz->id;
        $this->name = $sz->name;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();
        $sz = BouquetSize::findOrFail($this->size_id);
        $sz->update(['name' => $this->name]);
        $this->resetForm();
        $this->loadSizes();
        session()->flash('success', 'Ukuran berhasil diupdate!');
    }

    public function delete($id)
    {
        BouquetSize::destroy($id);
        $this->loadSizes();
        session()->flash('success', 'Ukuran berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.bouquet-size-crud');
    }
}
