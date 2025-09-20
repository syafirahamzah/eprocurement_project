@extends('layouts.customer')

@section('title', 'Konfirmasi Pemesanan')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 bg-white shadow rounded-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">📦 Konfirmasi Pemesanan</h1>

    <div class="mb-6 space-y-2">
        <p><span class="font-semibold">ID Pesanan:</span> {{ $order->id }}</p>
        <p><span class="font-semibold">Status:</span> 
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                {{ $order->status == 'diproses' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white' }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>
        <p><span class="font-semibold">Produk:</span> {{ $order->product->name }}</p>
        <p><span class="font-semibold">Harga Disepakati:</span> Rp {{ number_format($order->agreed_price, 0, ',', '.') }}</p>
    </div>

    <form action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="negotiation_id" value="{{ $order->negotiation_id }}">
        <input type="hidden" name="product_id" value="{{ $order->product_id }}">
        <input type="hidden" name="vendor_id" value="{{ $order->vendor_id }}">
        <input type="hidden" name="agreed_price" value="{{ $order->agreed_price }}">

        <!-- Pilihan Metode Pembayaran -->
        <div>
            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran:</label>
            <select name="payment_method" id="payment_method" class="w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="akad" selected>Bayar Saat Akad (Kasbon)</option>
                <option value="cod">Bayar di Tempat (COD)</option>
            </select>
        </div>

        <!-- Upload Dokumen Akad -->
    <div id="bukti_akad" class="hidden">
        <label for="agreement_document" class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen Akad:</label>
        <input type="file" name="agreement_document" accept=".pdf" 
               class="w-full border border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-blue-500 file:text-white">
        <p class="text-sm text-gray-500 mt-1">Dokumen harus berupa PDF (maks 2MB)</p>
    </div>
    <!-- Upload KTP -->
<div class="mt-4">
    <label for="ktp_document" class="block text-sm font-medium text-gray-700 mb-1">Upload Foto KTP:</label>
    <input type="file" name="ktp_document" accept=".jpg,.jpeg,.png,.pdf" required
           class="w-full border border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-gray-500 file:text-white">
    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, atau PDF (maks 2MB)</p>
</div>


    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md">Konfirmasi & Buat Pesanan</button>
</form>

    <div class="mt-6">
        <a href="{{ route('customer.orders') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Daftar Pesanan</a>
    </div>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function () {
        const showAkadDoc = this.value === 'akad';
    // Tampilkan input dokumen akad untuk "Akad"
        document.getElementById('bukti_akad').classList.toggle('hidden', !showAkadDoc);

    });
</script>
@endsection
