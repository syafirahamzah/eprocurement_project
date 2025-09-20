@extends('layouts.customer')

@section('title', 'Pilih Vendor')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📦 Pilih Vendor</h1>

    {{-- Cek jika data vendor kosong --}}
    @if($vendors->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded border border-yellow-300">
            Belum ada vendor tersedia saat ini.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Loop data vendor --}}
            @foreach($vendors as $vendor)
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-lg transition">
                    <div class="mb-4">
                        {{-- Pastikan property name dan email memang ada di model vendor --}}
                        <h2 class="text-xl font-bold text-gray-800">{{ $vendor->name }}</h2>
                        <p class="text-gray-600">{{ $vendor->email }}</p>
                        
                        {{-- Optional: tampilkan kategori jika ada --}}
                        <p class="text-sm text-gray-500 mt-1">
                            Kategori: {{ $vendor->category ?? 'Tidak Diketahui' }}
                        </p>
                    </div>
                    
                    {{-- Tombol ke e-Katalog vendor --}}
                    <a href="{{ route('customer.eCatalog', $vendor->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        🔍 Lihat Produk
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
