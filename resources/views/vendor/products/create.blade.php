@extends('layouts.vendor')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Tambah Produk Baru</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor.products.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-medium text-sm">Nama Produk</label>
            <input type="text" name="name" id="name" class="w-full border px-3 py-2 rounded"
                value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium text-sm">Deskripsi</label>
            <textarea name="description" id="description" rows="3" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block font-medium text-sm">Harga</label>
            <input type="number" name="price" id="price" class="w-full border px-3 py-2 rounded"
                value="{{ old('price') }}" required step="0.01">
        </div>

        <div class="mb-4">
            <label for="stock" class="block font-medium text-sm">Stok</label>
            <input type="number" name="stock" id="stock"
       value="{{ old('stock', $product->stock ?? 0) }}"
       required min="0" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="unit" class="block font-medium text-sm">Satuan</label>
            <input type="text" name="unit" id="unit" class="w-full border px-3 py-2 rounded"
                value="{{ old('unit') }}">
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
