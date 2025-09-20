<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 flex items-center">🧾 <span class="ml-2">Struk Pembelian</span></h1>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="font-semibold">Vendor:</p>
                <p>{{ Auth::user()->name }}</p>
                <p>{{ Auth::user()->address ?? 'Alamat belum tersedia' }}</p>
            </div>
            <div>
                <p class="font-semibold">Customer:</p>
                <p>{{ $order->customer->name ?? '-' }}</p>
                <p>{{ $order->customer->address ?? 'Alamat belum tersedia' }}</p>
            </div>
            <div>
                <p class="font-semibold">Tanggal Pesanan:</p>
                <p>{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="font-semibold">Status:</p>
                <p>{{ ucfirst($order->status) }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Produk</th>
                        <th class="p-2 border">Jumlah</th>
                        <th class="p-2 border">Harga</th>
                        <th class="p-2 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="p-2 border">{{ $order->product->name }}</td>
                        <td class="p-2 border">{{ $order->quantity }} {{ $order->product->unit }}</td>
                        <td class="p-2 border">Rp {{ number_format($order->product->price, 0, ',', '.') }}</td>
                        <td class="p-2 border">Rp {{ number_format($order->product->price * $order->quantity, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="p-2 border text-right font-semibold">Total</td>
                        <td class="p-2 border text-center font-semibold">Rp {{ number_format($order->product->price * $order->quantity, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-right mt-6">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                🖨 Cetak Struk
            </button>
        </div>
    </div>

</body>
</html>
