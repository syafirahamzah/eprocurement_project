@extends('layouts.vendor')

@section('title', 'Daftar Produk')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('vendor.products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if ($products->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white p-4 rounded shadow border">
                    <h3 class="text-lg font-bold text-blue-700">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                    <p class="mt-2 text-sm"><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="text-sm"><strong>Stok:</strong> {{ $product->stock }}</p>
                    <p class="text-sm"><strong>Satuan:</strong> {{ $product->unit ?? '-' }}</p>

                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('vendor.products.edit', $product->id) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </a>

                        <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus produk ini beserta semua data terkait?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Belum ada produk yang ditambahkan.</p>
    @endif
</div>
@endsection
