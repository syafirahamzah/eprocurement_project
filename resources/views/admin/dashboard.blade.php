@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">📊 Ringkasan Sistem</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded shadow text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $totalOrders }}</div>
            <div class="mt-2 text-gray-700">Total Pesanan</div>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <div class="text-3xl font-bold text-green-600">{{ $totalProducts }}</div>
            <div class="mt-2 text-gray-700">Total Produk</div>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <div class="text-3xl font-bold text-indigo-600">{{ $totalVendors }}</div>
            <div class="mt-2 text-gray-700">Total Vendor</div>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <div class="text-3xl font-bold text-yellow-600">{{ $totalCustomers }}</div>
            <div class="mt-2 text-gray-700">Total Customer</div>
        </div>
    </div>
</div>
@endsection
