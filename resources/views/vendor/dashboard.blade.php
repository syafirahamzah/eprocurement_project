@extends('layouts.vendor')

@section('title', '📊 Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- Statistik Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h2 class="text-sm text-gray-500 mb-2">Total Produk</h2>
            <p class="text-3xl font-bold text-blue-600">{{ $totalProducts }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h2 class="text-sm text-gray-500 mb-2">Pesanan Masuk</h2>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalOrders }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h2 class="text-sm text-gray-500 mb-2">Dalam Proses</h2>
            <p class="text-3xl font-bold text-yellow-600">{{ $processingOrders }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h2 class="text-sm text-gray-500 mb-2">Pesanan Selesai</h2>
            <p class="text-3xl font-bold text-green-600">{{ $completedOrders }}</p>
        </div>
    </div>

    {{-- Section Tambahan --}}
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h2 class="text-lg font-semibold mb-4">🔔 Notifikasi Terbaru</h2>
        @if($recentNotifications->isEmpty())
            <p class="text-gray-500 text-sm">Belum ada notifikasi terbaru.</p>
        @else
            <ul class="text-sm text-gray-700 space-y-2">
                @foreach($recentNotifications as $notif)
    <li class="border-b pb-2">
        <a href="{{ $notif->link_redirect ?? '#' }}" class="text-blue-600 hover:underline">
            {{ $notif->message }}
        </a>
        <span class="text-xs text-gray-400">({{ $notif->created_at->diffForHumans() }})</span>
    </li>
@endforeach

            </ul>
        @endif
    </div>
</div>
@endsection
