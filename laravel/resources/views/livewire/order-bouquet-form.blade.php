<div class="p-4 space-y-4">
    <h2 class="text-xl font-bold">Form Pemesanan Buket</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded">{{ session('success') }}</div>
    @endif

    <!-- Step Indicator -->
    <div class="flex space-x-2 mb-4">
        <div class="px-3 py-1 rounded-full text-xs font-bold {{ $step == 1 ? 'bg-pink-500 text-white' : 'bg-gray-200' }}">1. Data Pemesan</div>
        <div class="px-3 py-1 rounded-full text-xs font-bold {{ $step == 2 ? 'bg-pink-500 text-white' : 'bg-gray-200' }}">2. Pilih Bouquet</div>
        <div class="px-3 py-1 rounded-full text-xs font-bold {{ $step == 3 ? 'bg-pink-500 text-white' : 'bg-gray-200' }}">3. Konfirmasi</div>
    </div>

    <!-- Step 1: Data Pemesan & Pengiriman -->
    @if($step == 1)
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Nama Pemesan</label>
            <input type="text" wire:model.defer="customer_name" class="w-full border p-1 rounded" />
            @error('customer_name') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Nama Penerima</label>
            <input type="text" wire:model.defer="receiver_name" class="w-full border p-1 rounded" />
            @error('receiver_name') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Tanggal & Waktu Pengambilan</label>
            <input type="datetime-local" wire:model.defer="pickup_datetime" class="w-full border p-1 rounded" />
            @error('pickup_datetime') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Metode Pengiriman</label>
            <select wire:model.defer="delivery_method" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                <option value="ambil">Ambil Langsung</option>
                <option value="grab">Grab</option>
                <option value="gocar">Gocar</option>
                <option value="custom">Custom</option>
            </select>
            @error('delivery_method') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="col-span-2">
            <label>Alamat Pengiriman</label>
            <textarea wire:model.defer="delivery_address" class="w-full border p-1 rounded"></textarea>
        </div>
        <div class="col-span-2">
            <label>Isi Kartu Ucapan</label>
            <textarea wire:model.defer="greeting_card" class="w-full border p-1 rounded"></textarea>
        </div>
    </div>
    <div class="flex justify-end mt-4">
        <button wire:click="nextStep" class="bg-pink-500 text-white px-4 py-2 rounded">Lanjut &rarr;</button>
    </div>
    @endif

    <!-- Step 2: Pilih Bouquet & Detail -->
    @if($step == 2)
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Kategori Bouquet</label>
            <select wire:model="category_id" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Produk Bouquet</label>
            <select wire:model="bouquet_id" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                @foreach($bouquets as $bq)
                    <option value="{{ $bq->id }}">{{ $bq->name }}</option>
                @endforeach
            </select>
            @error('bouquet_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Tipe Ukuran</label>
            <select wire:model="size_id" class="w-full border p-1 rounded">
                <option value="">-- Pilih --</option>
                @foreach($sizes as $sz)
                    <option value="{{ $sz->id }}">{{ $sz->name }}</option>
                @endforeach
            </select>
            @error('size_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Potongan Harga (Diskon)</label>
            <input type="number" wire:model="discount" class="w-full border p-1 rounded" min="0" />
        </div>
    </div>
    <div class="mt-4">
        <label class="font-bold">Deskripsi Bouquet:</label>
        <div class="bg-gray-100 p-2 rounded mb-2">{{ $bouquetDescription }}</div>
        <label class="font-bold">Isi Bouquet:</label>
        <table class="w-full text-sm mb-2">
            <thead>
                <tr class="bg-gray-200">
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bouquetItems as $idx => $item)
                <tr>
                    <td>{{ $item['product_name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>
                        @if($item['stock'] <= 0)
                            <span class="text-red-500">Stok Habis</span>
                        @else
                            {{ $item['stock'] }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-between font-bold">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
        </div>
        <div class="flex justify-between font-bold">
            <span>Total:</span>
            <span>Rp {{ number_format($total,0,',','.') }}</span>
        </div>
    </div>
    <div class="flex justify-between mt-4">
        <button wire:click="prevStep" class="bg-gray-300 px-4 py-2 rounded">&larr; Kembali</button>
        <button wire:click="nextStep" class="bg-pink-500 text-white px-4 py-2 rounded">Lanjut &rarr;</button>
    </div>
    @endif

    <!-- Step 3: Konfirmasi & Simpan -->
    @if($step == 3)
    <div class="bg-gray-50 p-4 rounded">
        <h3 class="font-bold mb-2">Konfirmasi Data Pemesanan</h3>
        <div><b>Pemesan:</b> {{ $customer_name }}</div>
        <div><b>Penerima:</b> {{ $receiver_name }}</div>
        <div><b>Pengambilan:</b> {{ $pickup_datetime }}</div>
        <div><b>Metode:</b> {{ $delivery_method }}</div>
        <div><b>Alamat:</b> {{ $delivery_address }}</div>
        <div><b>Kartu Ucapan:</b> {{ $greeting_card }}</div>
        <hr class="my-2">
        <div><b>Bouquet:</b> {{ optional($bouquets->firstWhere('id', $bouquet_id))->name }}</div>
        <div><b>Kategori:</b> {{ optional($categories->firstWhere('id', $category_id))->name }}</div>
        <div><b>Ukuran:</b> {{ optional($sizes->firstWhere('id', $size_id))->name }}</div>
        <div><b>Subtotal:</b> Rp {{ number_format($subtotal,0,',','.') }}</div>
        <div><b>Diskon:</b> Rp {{ number_format($discount,0,',','.') }}</div>
        <div><b>Total:</b> <span class="font-bold text-pink-600">Rp {{ number_format($total,0,',','.') }}</span></div>
    </div>
    <div class="flex justify-between mt-4">
        <button wire:click="prevStep" class="bg-gray-300 px-4 py-2 rounded">&larr; Kembali</button>
        <button wire:click="submitOrder" class="bg-pink-500 text-white px-4 py-2 rounded">Simpan Pemesanan</button>
    </div>
    @endif
</div>