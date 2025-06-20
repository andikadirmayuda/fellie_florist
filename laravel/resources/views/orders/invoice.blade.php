<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        @media print {
            body, html {
                width: 210mm;
                height: 297mm;
                margin: 0 auto;
                padding: 0;
                box-sizing: border-box;
            }
            .invoice-container {
                width: 190mm;
                min-height: 277mm;
                margin: 0 auto;
                padding: 10mm;
                background: #fff;
                box-sizing: border-box;
            }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
        }
        .invoice-container {
            background: #fff;
            max-width: 800px;
            margin: 20px auto;
            padding: 32px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            color: #1a56db;
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .invoice-to {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8fafc;
        }
        .total-section {
            margin-top: 20px;
        }
        .total-section table {
            width: 300px;
            margin-left: auto;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #1a56db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 8px;">
            Print Invoice
        </button>        <a href="{{ route('orders.share-whatsapp', $order) }}" style="padding: 8px 16px; background: #25D366; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
            Share via WhatsApp
        </a>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Fellie Florist</h1>
            <p>Invoice #{{ $order->order_number }}</p>
        </div>

        <div class="invoice-details">
            <div class="invoice-details-grid">
                <div class="invoice-to">
                    <h3>Invoice To:</h3>
                    <p>
                        <strong>{{ $order->customer ? $order->customer->name : '[Deleted Customer]' }}</strong><br>
                        {{ $order->customer ? ($order->customer->phone ?: 'No Phone') : 'No Contact Info' }}<br>
                        {{ $order->customer ? ($order->customer->email ?: 'No Email') : '' }}
                    </p>
                </div>
                <div class="invoice-info">
                    <h3>Order Details:</h3>
                    <p>
                        <strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}<br>
                        <strong>Pickup Date:</strong> {{ $order->pickup_date->format('d M Y H:i') }}<br>
                        <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
                        <strong>Delivery Method:</strong> {{ $order->delivery_method_label }}
                        @if($order->delivery_method !== 'pickup')
                        <br><strong>Delivery Address:</strong> {{ $order->delivery_address }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Tipe Harga</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->price_type)) }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                @if($order->delivery_fee > 0)
                <tr>
                    <td><strong>Biaya Pengiriman:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Total + Ongkir:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->total + $order->delivery_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Uang Muka:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->down_payment, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Sisa Pembayaran:</strong></td>
                    <td class="text-right">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your order!</p>
            <p>For any questions, please contact us at: 08XXXXXXXXXX</p>
        </div>
    </div>

    <script>
        function shareToWhatsApp() {
            const orderNumber = '{{ $order->order_number }}';
            const customerName = '{{ $order->customer->name }}';
            const total = 'Rp {{ number_format($order->total + $order->delivery_fee, 0, ',', '.') }}';
            const remainingPayment = 'Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}';
            const pickupDate = '{{ $order->pickup_date->format('d M Y H:i') }}';
            
            const message = `*Invoice Fellie Florist*%0A
Order: ${orderNumber}%0A
Customer: ${customerName}%0A
Pickup: ${pickupDate}%0A
Total: ${total}%0A
Sisa Pembayaran: ${remainingPayment}%0A%0A
Terima kasih telah berbelanja di Fellie Florist!`;
            
            window.open(`https://wa.me/?text=${message}`);
        }
    </script>
</body>
</html>
