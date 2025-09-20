<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Akad Kasbon</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 40px;
        }

        h2 {
            text-align: center;
            text-decoration: underline;
            margin-bottom: 25px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .signature div {
            width: 48%;
            text-align: center;
        }

        .ktp-preview {
            margin-top: 15px;
        }

        .ktp-preview img {
            border: 1px solid #000;
            max-width: 250px;
            max-height: 150px;
        }
    </style>
</head>
<body>

    <h2>SURAT PERJANJIAN AKAD KASBON</h2>

    <p>Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>, telah terjadi kesepakatan antara pihak-pihak sebagai berikut:</p>

    <p class="section-title">1. Pihak Pertama (Customer)</p>
    <p>
        Nama: {{ $customer->name }}<br>
        Email: {{ $customer->email }}<br>
        Alamat: {{ $customer->address ?? '-' }}
    </p>

    @if($customer->ktp_path)
        <div class="ktp-preview">
            <p>Lampiran KTP Customer:</p>
            <img src="{{ public_path('storage/' . $customer->ktp_path) }}" alt="KTP Customer">
        </div>
    @endif

    <p class="section-title">2. Pihak Kedua (Vendor)</p>
    <p>
        Nama: {{ $vendor->name }}<br>
        Email: {{ $vendor->email }}<br>
        Alamat: {{ $vendor->address ?? '-' }}
    </p>

    <p class="section-title">3. Detail Pesanan</p>
    <p>
        Nama Produk: {{ $product->name }}<br>
        Jumlah: {{ $order->quantity }}<br>
        Harga Satuan: Rp {{ number_format($product->price, 0, ',', '.') }}<br>
        Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}
    </p>

    <p class="section-title">4. Ketentuan Pembayaran</p>
    <p>
        Pihak Pertama sepakat untuk melakukan pembayaran saat akad (kasbon) kepada Pihak Kedua, dan akan melunasi pembayaran sesuai waktu dan ketentuan yang disepakati oleh kedua belah pihak.
    </p>

    <p class="section-title">5. Penutup</p>
    <p>
        Demikian surat perjanjian ini dibuat dan disepakati oleh kedua belah pihak dalam keadaan sadar, tanpa paksaan dari pihak manapun. Surat ini bersifat sah dan mengikat secara hukum bagi kedua pihak.
    </p>
    <table style="width: 100%; margin-top: 60px; text-align: center;">
    <tr>
        <td style="width: 50%;">
            Pihak Pertama<br><br><br><br><br>
            <strong>{{ $customer->name }}</strong>
        </td>
        <td style="width: 50%;">
            Pihak Kedua<br><br><br><br><br>
            <strong>{{ $vendor->name }}</strong>
        </td>
    </tr>
</table>


</body>
</html>
