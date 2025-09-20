@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">🔍 Detail Pesanan</h2>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p><strong>Kode Pesanan:</strong> {{ $order->order_code ?? 'ORD-' . $order->id }}</p>
            <p><strong>Produk:</strong> {{ $order->product->name }}</p>
            <p><strong>Kategori:</strong> {{ $order->product->category->name ?? '-' }}</p>
            <p><strong>Jumlah:</strong> {{ $order->quantity ?? '-' }}</p>
            <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
            <p><strong>Alamat Customer:</strong> {{ $order->customer->address ?? '-' }}</p>
        </div>
        <div>
            <p><strong>Vendor:</strong> {{ $order->vendor->name }}</p>
            <p><strong>No. HP Vendor:</strong> {{ $order->vendor->phone ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method ?? '-') }}</p>
            <p><strong>Total:</strong> Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
            <p><strong>Dipesan Pada:</strong> {{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
            @if($order->completed_at)
            <p><strong>Selesai Pada:</strong> {{ $order->completed_at->translatedFormat('d M Y H:i') }}</p>
            @endif
        </div>
    </div>

    <hr class="my-4">

    <h3 class="font-semibold mb-2">📦 Informasi Pengiriman</h3>
    <p><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address ?? '-' }}</p>
    <p><strong>Estimasi Tiba:</strong>
        {{ optional($order->tracking)->estimated_delivery ? \Carbon\Carbon::parse($order->tracking->estimated_delivery)->translatedFormat('d M Y H:i') : '-' }}
    </p>
    <p><strong>Status Tracking:</strong> {{ optional($order->tracking)->status ?? '-' }}</p>
    <p><strong>Terakhir Diperbarui:</strong>
        {{ optional($order->tracking)->updated_at ? $order->tracking->updated_at->translatedFormat('d M Y H:i') : '-' }}
    </p>

    @if($order->receipt_url)
    <p><strong>Struk Pembayaran:</strong>
        <a href="{{ $order->receipt_url }}" class="text-blue-600 underline" target="_blank">Download</a>
    </p>
    @endif
</div>
@endsection
