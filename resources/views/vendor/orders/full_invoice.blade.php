<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk & Kontrak Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 10px; }
        .section { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
    </style>
</head>
<body>

    <h2>🧾 STRUK PEMBELIAN</h2>

    <div class="section">
        <p><strong>Nama Customer:</strong> {{ $order->customer->name }}</p>
        <p><strong>Nama Vendor:</strong> {{ $order->vendor->name }}</p>
        <p><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        <p><strong>Status Pembayaran:</strong> {{ ucfirst($order->payment_status) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }} {{ $order->product->unit }}</td>
                <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($order->price * $order->quantity, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($order->price * $order->quantity, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="section">
        <h2>📜 PERNYATAAN PERSETUJUAN PEMBAYARAN</h2>
        <p>Dengan ini saya menyetujui pembayaran dilakukan saat akad serah terima barang. Saya bertanggung jawab melunasi pembayaran sesuai harga yang telah disepakati.</p>

        <table>
            <tr>
                <th>Waktu Persetujuan</th>
                <td>{{ \Carbon\Carbon::parse($order->agreement->agreed_at)->translatedFormat('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>IP Address</th>
                <td>{{ $order->agreement->ip_address ?? '-' }}</td>
            </tr>
            <tr>
                <th>Pernyataan</th>
                <td>{{ $order->agreement->agreement_text }}</td>
            </tr>
        </table>

        <p style="margin-top: 40px;">Hormat Saya,</p>
        <p style="margin-top: 60px;"><strong>{{ $order->customer->name }}</strong></p>
    </div>

</body>
</html>
