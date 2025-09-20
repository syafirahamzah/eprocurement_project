@extends('layouts.customer')

@section('title', '📦 Daftar Pesanan')

@section('content')

{{--Flash Success --}}
@if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 border border-green-200 shadow">
        {{ session('success') }}
    </div>
@endif

{{-- 🔍 Filter Tipe Pemesanan --}}
<form method="GET" action="{{ route('customer.orders') }}" class="mb-4">
    <label for="type" class="text-sm font-medium text-gray-700 mr-2">Tampilkan:</label>
    <select name="type" id="type" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1 text-sm">
        <option value="">Semua Pesanan</option>
        <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>Tanpa Negosiasi</option>
        <option value="negotiation" {{ request('type') == 'negotiation' ? 'selected' : '' }}>Dengan Negosiasi</option>
    </select>
</form>

{{-- 📋 Tabel Pesanan --}}
<div class="overflow-x-auto bg-white rounded-lg shadow border">
    <table class="min-w-full table-auto text-sm text-left">
        <thead class="bg-gray-50 font-semibold text-gray-700">
            <tr>
                <th class="px-6 py-3">Order ID</th>
                <th class="px-6 py-3">Produk</th>
                <th class="px-6 py-3">Vendor</th>
                <th class="px-6 py-3">Jumlah</th>
                <th class="px-6 py-3">Harga</th>
                <th class="px-6 py-3">Tipe Pemesanan</th>
                <th class="px-6 py-3">Metode Bayar</th>
                <th class="px-6 py-3">Status Bayar</th>
                <th class="px-6 py-3">Status Pesanan</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-t">
                <td class="px-6 py-4">{{ $order->id }}</td>
                <td class="px-6 py-4">{{ $order->product->name ?? '-' }}</td>
                <td class="px-6 py-4">{{ $order->vendor->name ?? '-' }}</td>
                <td class="px-6 py-4">{{ $order->quantity }}</td>
                <td class="px-6 py-4">Rp {{ number_format($order->agreed_price, 0, ',', '.') }}</td>

                {{-- Tipe Pemesanan --}}
                <td class="px-6 py-4">
                    @if($order->negotiation_id)
                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                            🤝 Negosiasi
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                            🛒 Reguler
                        </span>
                    @endif
                </td>

                {{-- Metode Pembayaran --}}
                <td class="px-6 py-4 text-xs">
                    @if ($order->payment_method === 'akad')
                        Bayar Saat Akad
                    @else
                        Bayar di Tempat
                    @endif
                </td>

                {{-- Status Pembayaran --}}
                <td class="px-6 py-4 text-xs">
                    <span class="px-2 py-1 rounded-full font-medium
                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' :
                           ($order->payment_status === 'akad' ? 'bg-yellow-100 text-yellow-700' :
                           ($order->payment_status === 'pending' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')) }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                    @if ($order->payment_method === 'akad' && $order->agreement_document)
                        <br>
                        <a href="{{ asset('storage/' . $order->agreement_document) }}" target="_blank" class="text-blue-500 underline text-xs">
                            Lihat Akad
                        </a>
                    @endif
                </td>

                {{-- Status Pesanan --}}
                <td class="px-6 py-4">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $order->status === 'dikirim' ? 'bg-blue-100 text-blue-600' :
                           ($order->status === 'diproses' ? 'bg-yellow-100 text-yellow-700' :
                           ($order->status === 'selesai' ? 'bg-green-100 text-green-700' :
                           ($order->status === 'dibatalkan' ? 'bg-red-100 text-red-700' :
                           ($order->status === 'menunggu konfirmasi' ? 'bg-orange-100 text-orange-700' :
                           'bg-gray-100 text-gray-600')))) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>

                {{-- Aksi --}}
                <td class="px-6 py-4 space-y-1 text-xs">
                    @if($order->status === 'selesai' || $order->status === 'dikirim')
                        <a href="{{ route('customer.order.akad', $order->id) }}"
                           class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 block text-center">
                            Download Invoice
                        </a>
                    @endif

                   @if ($order->status === 'menunggu konfirmasi')
    <form action="{{ route('customer.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
        @csrf
        <button type="submit" class="btn btn-danger">Batalkan</button>
    </form>
@endif

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="px-6 py-4 text-center text-gray-500">Belum ada pesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
