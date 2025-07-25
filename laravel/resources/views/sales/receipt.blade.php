<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f5f6fa;
        }

        .receipt {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            padding: 32px 28px 24px 28px;
            border: 1px solid #e5e7eb;
        }

        .brand-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 18px;
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            margin: 0 auto 8px auto;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 1px;
            text-align: center;
        }

        .brand-address {
            font-size: 0.95rem;
            color: #666;
            text-align: center;
            margin-bottom: 2px;
        }

        .receipt h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 18px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 18px;
        }

        .info-table td {
            color: #444;
            padding: 2px 0;
            font-size: 0.97rem;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .items-table th,
        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.97rem;
        }

        .items-table th {
            background: #f8fafc;
            color: #555;
            font-weight: 600;
            text-align: left;
        }

        .items-table td {
            color: #222;
        }

        .items-table td.text-right,
        .items-table th.text-right {
            text-align: right;
        }

        .items-table td.text-center,
        .items-table th.text-center {
            text-align: center;
        }

        .total-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
            margin-bottom: 18px;
        }

        .total-label {
            color: #444;
            font-weight: 600;
        }

        .total-value {
            color: #2563eb;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .footer {
            text-align: center;
            color: #888;
            font-size: 0.95rem;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="brand-header"
            style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; margin-bottom: 18px;">
            <img src="{{ public_path('logo-fellie.png') }}" alt="Logo" class="brand-logo"
                style="display:block; margin:0 auto 8px auto;">
            <div class="brand-title"
                style="font-size: 1.25rem; font-weight: 700; color: #2563eb; letter-spacing: 1px; text-align:center;">
                Fellie Florist</div>
            <div class="brand-address" style="font-size: 0.95rem; color: #666; text-align: center; margin-bottom: 2px;">
                Jl. Mawar No. 123, Jakarta<br>Telp: 08XXXXXXXXXX</div>
        </div>
        <h2>Struk Penjualan</h2>
        <table class="info-table">
            <tr>
                <td>No. Penjualan</td>
                <td style="width:10px;">:</td>
                <td class="text-blue-700" style="color:#2563eb;font-weight:600;">{{ $sale->order_number }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($sale->order_time)->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>:</td>
                <td>{{ ucfirst($sale->payment_method) }}</td>
            </tr>
        </table>
        <h3 style="font-size:1.08rem;font-weight:600;color:#222;margin-bottom:8px;">Item Penjualan</h3>
        <div style="overflow-x:auto;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Produk</th>
                        <th>Tipe Harga</th>
                        <th class="text-right">Harga</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $item->price_type)) }}</td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="total-box">
            <span class="total-label">Total</span>
            <span class="total-value">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
        </div>
        <div class="footer">
            Terima kasih telah berbelanja di Fellie Florist!<br>
            <span>Untuk pertanyaan, hubungi: 08XXXXXXXXXX</span>
        </div>
    </div>
</body>

</html>