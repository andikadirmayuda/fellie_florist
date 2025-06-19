<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} - Fellie Florist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-indigo-600">Fellie Florist</h1>
                <p class="text-gray-600">Invoice #{{ $order->order_number }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Invoice To:</h3>
                    <p class="text-gray-600">
                        <strong>{{ $order->customer ? $order->customer->name : '[Deleted Customer]' }}</strong><br>
                        {{ $order->customer ? ($order->customer->phone ?: 'No Phone') : 'No Contact Info' }}<br>
                        {{ $order->customer ? ($order->customer->email ?: 'No Email') : '' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Order Details:</h3>
                    <p class="text-gray-600">
                        <strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}<br>
                        <strong>Pickup Date:</strong> {{ $order->pickup_date->format('d M Y H:i') }}<br>
                        <strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span><br>
                        <strong>Delivery Method:</strong> {{ $order->delivery_method_label }}
                        @if($order->delivery_method !== 'pickup')
                        <br><strong>Delivery Address:</strong> {{ $order->delivery_address }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="py-2 px-4">Produk</th>
                            <th class="py-2 px-4">Tipe Harga</th>
                            <th class="py-2 px-4 text-right">Harga</th>
                            <th class="py-2 px-4 text-right">Jumlah</th>
                            <th class="py-2 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-2 px-4">{{ $item->product->name }}</td>
                            <td class="py-2 px-4">{{ ucfirst(str_replace('_', ' ', $item->price_type)) }}</td>
                            <td class="py-2 px-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-right">{{ $item->qty }}</td>
                            <td class="py-2 px-4 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mb-8">
                <div class="w-64">
                    <div class="border-t pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Subtotal:</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        @if($order->delivery_fee > 0)
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Biaya Pengiriman:</span>
                            <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Total + Ongkir:</span>
                            <span>Rp {{ number_format($order->total + $order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between mb-2 text-green-600">
                            <span class="font-semibold">Uang Muka:</span>
                            <span>Rp {{ number_format($order->down_payment, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-blue-600 font-bold">
                            <span>Remaining Payment:</span>
                            <span>Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center text-gray-600 text-sm">
                <p>Thank you for your order!</p>
                <p class="mt-2">For any questions, please contact us at: 08XXXXXXXXXX</p>
                <p class="mt-4">Fellie Florist &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4 print:hidden">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            Print Invoice
        </button>
    </div>
</body>
</html>
