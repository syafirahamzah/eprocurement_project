@extends('layouts.customer')

@section('title', 'Detail Produk')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $product->name }}</h2>

        <p class="text-gray-600 mb-2">
            <strong>Vendor:</strong> {{ $product->vendor->name ?? '-' }}
        </p>

        <p class="text-gray-600 mb-2">
            <strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>

        <p class="text-gray-600 mb-6">
            <strong>Deskripsi:</strong><br>
            {{ $product->description ?? 'Tidak ada deskripsi.' }}
        </p>

        <div class="flex gap-4">
            <a href="{{ route('customer.negotiation.form', $product->id) }}"
               class="bg-yellow-500 text-white px-5 py-2 rounded hover:bg-yellow-600">
                💬 Ajukan Negosiasi
            </a>

            <button onclick="openDetailModal()"
               class="bg-gray-300 text-gray-800 px-5 py-2 rounded hover:bg-gray-400">
                🔍 Lihat Detail
            </button>

            <button onclick="openOrderModal()"
               class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                🛒 Pesan Sekarang
            </button>
        </div>

        <div class="mt-6">
            <a href="{{ route('customer.eCatalog') }}"
               class="text-sm text-indigo-600 hover:underline">
                ⬅ Kembali ke Katalog
            </a>
        </div>
    </div>
</div>

{{-- 🔲 Modal Detail Produk --}}
<div id="detailModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded shadow-lg w-96">
        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Detail</h2>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700 text-xl">×</button>
        </div>

        <div class="p-4 space-y-4 text-sm text-gray-700">
            <div class="flex justify-between border-b pb-2">
                <span>Nama Produk</span>
                <span>: {{ $product->name }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span>Harga Produk</span>
                <span>: Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span>Kode Produk</span>
                <span>: {{ $product->code ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span>Merek Produk</span>
                <span>: {{ $product->brand ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span>Berat Produk</span>
                <span>: {{ $product->weight ?? '-' }} kg</span>
            </div>
        </div>
    </div>
</div>

{{-- 🔲 Modal Form Pesan --}}
<div id="orderModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
        <h2 class="text-lg font-bold mb-3 text-gray-800">Pesan Produk</h2>
        <form action="{{ route('customer.order.direct', $product->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1"
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="flex justify-between">
                <button type="button" onclick="closeOrderModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                     Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openDetailModal() {
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function openOrderModal() {
        document.getElementById('orderModal').classList.remove('hidden');
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
</script>
@endsection
