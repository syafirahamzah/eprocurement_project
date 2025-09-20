@extends('layouts.customer')

@section('title', 'Permintaan Barang')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">Ajukan Permintaan Barang</h2>

    <!-- Menampilkan informasi vendor yang dipilih -->
    <div class="mb-4">
        <h3 class="font-semibold text-lg">Vendor yang Dipilih: {{ $vendor->name }}</h3>
        <p class="text-sm text-gray-600">{{ $vendor->email }}</p>
    </div>

    <!-- Formulir Permintaan -->
    <form action="{{ route('customer.storeRequest') }}" method="POST">
        @csrf

        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

        <!-- Barang -->
        <div class="mb-4">
            <label for="barang" class="block text-sm font-medium text-gray-700">Barang</label>
            <input type="text" id="barang" name="barang" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Jumlah -->
        <div class="mb-4">
            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
            <input type="number" id="jumlah" name="jumlah" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Keterangan (Opsional) -->
        <div class="mb-6">
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea id="keterangan" name="keterangan" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md">Kirim Permintaan</button>
    </form>
</div>
@endsection
