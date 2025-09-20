<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontrak Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { padding: 6px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="title">🧾 Surat Pernyataan Persetujuan Pembayaran</div>

    <p>Yang bertanda tangan di bawah ini:</p>

    <p><strong>Nama Customer:</strong> {{ $order->customer->name }}</p>
    <p><strong>Produk:</strong> {{ $order->product->name }}</p>
    <p><strong>Jumlah:</strong> {{ $order->quantity }} {{ $order->product->unit }}</p>
    <p><strong>Harga Disepakati:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>

    <p>Dengan ini menyatakan bahwa saya menyetujui pembayaran dilakukan saat akad serah terima barang. Saya bertanggung jawab untuk melunasi pembayaran sesuai kesepakatan.</p>

    <br>
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

    <br><br><br>
    <p>Hormat Saya,</p>
    <p style="margin-top: 50px;"><strong>{{ $order->customer->name }}</strong></p>
</body>
</html>
