@extends('layouts.customer')

@section('title', '🏠 Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow text-center">
            <p class="text-gray-500 text-sm mb-1">Total Pesanan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <p class="text-gray-500 text-sm mb-1">Pesanan Dikirim</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $shippedOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <p class="text-gray-500 text-sm mb-1">Pesanan Selesai</p>
            <p class="text-2xl font-bold text-green-600">{{ $completedOrders }}</p>
        </div>
    </div>

    {{-- Navigasi cepat --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('customer.orders') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded shadow text-center">
            📦 Lihat Pesanan
        </a>
        <a href="{{ route('customer.history') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded shadow text-center">
            📜 Riwayat Pemesanan
        </a>
        <a href="{{ route('customer.profile') }}" class="bg-gray-600 hover:bg-gray-700 text-white p-4 rounded shadow text-center">
            ⚙️ Profil Saya
        </a>
    </div>
</div>
@endsection
