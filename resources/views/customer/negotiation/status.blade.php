@extends('layouts.customer')

@section('title', 'Status Negosiasi')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    @if ($order)
        <h1 class="text-2xl font-bold mb-6">📦 Status Negosiasi untuk Pesanan ID: {{ $order->id }}</h1>

        @if ($order->negotiation)
            <p>Status Negosiasi: 
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ 
                        $order->negotiation->status == 'menunggu' ? 'bg-yellow-500' : 
                        ($order->negotiation->status == 'disetujui' ? 'bg-green-500' : 'bg-red-500') 
                    }}">
                    {{ ucfirst($order->negotiation->status) }}
                </span>
            </p>
        @else
            <p>Negosiasi tidak ditemukan untuk pesanan ini.</p>
        @endif

        @if ($order->negotiation && $order->negotiation->status == 'disetujui')
            <a href="{{ route('customer.placeOrder', $order->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                Pesan Sekarang
            </a>
        @elseif ($order->negotiation && $order->negotiation->status == 'ditolak')
            <span class="text-red-500">Negosiasi Ditolak</span>
        @else
            <span class="text-yellow-500">Menunggu Persetujuan</span>
        @endif
    @else
        <p>Pesanan tidak ditemukan.</p>
    @endif
</div>
@endsection
