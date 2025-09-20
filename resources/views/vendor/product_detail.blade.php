@extends('layouts.vendor')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Detail & Edit Produk</h1>

    <form action="{{ route('vendor.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-lg shadow border space-y-4">
            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Satuan -->
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700">Satuan</label>
                <input type="text" id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <!-- Stok -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('vendor.products.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">⬅ Kembali</a>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">💾 Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
