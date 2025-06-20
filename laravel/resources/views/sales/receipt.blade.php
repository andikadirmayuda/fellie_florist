<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Struk Penjualan
        </h2>
    </x-slot>

    <div class="container py-8" style="max-width:400px;">
        <div class="card mt-4">
            <div class="card-body">
                <div>
                    <strong>No. Penjualan:</strong> {{ $sale->order_number }}<br>
                    <strong>Waktu:</strong> {{ $sale->order_time }}<br>
                    <strong>Metode Pembayaran:</strong> {{ ucfirst($sale->payment_method) }}<br>
                </div>
                <hr>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}<br><small>({{ $item->price_type }})</small></td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="d-flex justify-content-between">
                    <span><strong>Total</strong></span>
                    <span><strong>{{ number_format($sale->total, 0, ',', '.') }}</strong></span>
                </div>
                <div class="text-center mt-3">
                    <button onclick="window.print()" class="btn btn-primary">Print Struk</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
