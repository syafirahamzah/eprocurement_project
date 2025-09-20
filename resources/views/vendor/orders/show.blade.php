@extends('layouts.vendor')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">📄 Detail Pesanan #{{ $order->id }}</h1>

    {{-- Informasi Produk --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">🛒 Produk Dipesan</h2>
        <p><strong>Nama Produk:</strong> {{ $order->product->name }}</p>
        <p><strong>Jumlah:</strong> {{ $order->quantity }} unit</p>
        <p><strong>Harga Satuan:</strong> Rp {{ number_format($order->product->price, 0, ',', '.') }}</p>
        <p><strong>Total Disepakati:</strong>
            <span class="font-semibold text-green-700">Rp {{ number_format($order->agreed_price, 0, ',', '.') }}</span>
        </p>
    </div>

    {{-- Informasi Customer --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">👤 Informasi Customer</h2>
        <p><strong>Nama:</strong> {{ $order->customer->name }}</p>
        <p><strong>Email:</strong> {{ $order->customer->email }}</p>
        <p><strong>Alamat Tujuan:</strong> {{ $order->shipping_address ?? '-' }}</p>
    </div>

    {{-- Status Pengiriman --}}
    @php
        $trackingStatus = $order->tracking->status ?? 'diproses';
        $estimated = $order->tracking->estimated_delivery ?? null;
    @endphp

    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">🚚 Status Pengiriman</h2>
        <p>
            <strong>Status:</strong>
            <span class="inline-block px-3 py-1 text-xs rounded-full font-medium
                @if($trackingStatus === 'dikirim') bg-blue-100 text-blue-600
                @elseif($trackingStatus === 'selesai') bg-green-100 text-green-700
                @else bg-gray-100 text-gray-600 @endif">
                {{ ucfirst($trackingStatus) }}
            </span>
        </p>
        <p><strong>Estimasi Tiba:</strong>
            {{ $estimated ? \Carbon\Carbon::parse($estimated)->translatedFormat('d F Y H:i') : '-' }}
        </p>
    </div>

    {{-- Dokumen Akad dan KTP --}}
@if($order->payment_method === 'akad')
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">📑 Dokumen Akad Kasbon</h2>
        <p><strong>Metode Pembayaran:</strong> Bayar saat Akad / Kasbon</p>

        @if($order->akadDokumen)
            <p><strong>Dokumen KTP:</strong></p>
            <a href="{{ asset('storage/' . $order->akadDokumen->ktp_path) }}" target="_blank" class="text-blue-600 underline">
                📄 Lihat File KTP
            </a>

            <p class="mt-4"><strong>Dokumen Perjanjian Akad:</strong></p>
            <a href="{{ asset('storage/' . $order->akadDokumen->akad_file) }}" target="_blank"
               class="inline-block mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                📥 Cetak Perjanjian Akad
            </a>
        @else
            <p class="text-red-500">❌ Dokumen akad belum tersedia untuk pesanan ini.</p>
        @endif
    </div>
@endif


    @if($order->kasbon_agreement_path)
    <p><strong>Dokumen Perjanjian:</strong></p>
    <a href="{{ asset('storage/' . $order->kasbon_agreement_path) }}" target="_blank" class="inline-block mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        📥 Cetak Perjanjian Akad
    </a>
@endif


    {{-- Tombol Kembali --}}
    <div class="mt-6">
        <a href="{{ route('vendor.orders.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
            ← Kembali ke Daftar Pesanan
        </a>
    </div>
</div>
@endsection
