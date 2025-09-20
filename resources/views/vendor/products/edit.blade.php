@extends('layouts.vendor')

@section('title', 'Edit Produk')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Produk</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block font-medium text-sm">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium text-sm">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full border px-3 py-2 rounded">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="price" class="block font-medium text-sm">Harga</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="w-full border px-3 py-2 rounded" required step="0.01">
            </div>
<div class="mb-4">
    <label for="unit" class="block text-sm font-medium text-gray-700">Satuan</label>
    <input type="text" name="unit" id="unit" class="w-full border rounded px-3 py-2"
           value="{{ old('unit', $product->unit ?? '') }}">
</div>


            <!-- Stock -->
<div class="mb-4">
    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Produk</label>
    <input type="number" name="stock" id="stock"
       value="{{ old('stock', $product->stock ?? 0) }}"
       required min="0" class="w-full border rounded px-3 py-2">
    @error('stock')
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
</div>



            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
@endsection
