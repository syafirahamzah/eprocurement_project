@extends('layouts.customer')

@section('title', '📦 Monitoring Pesanan Anda')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($orders->isEmpty())
    <p class="text-gray-600">Belum ada pengiriman aktif.</p>
@else
    {{-- Input Pencarian --}}
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="Cari produk, vendor, atau status..." class="border py-2 rounded w-1/4 shadow">
    </div>

    {{-- Tabel Monitoring --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full table-auto border text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Produk</th>
                    <th class="px-4 py-2">Vendor</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Estimasi Tiba</th>
                    <th class="px-4 py-2">Tindakan</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                @foreach($orders as $order)
                    @php
                        $eta = \Carbon\Carbon::parse($order->tracking->estimated_delivery);
                        $now = \Carbon\Carbon::now();
                        $status = $order->tracking->status;
                        $isLate = $now->gt($eta) && $status !== 'diterima';
                        $orderCode = $order->order_code ?? 'ORD-' . $order->id;
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2 font-mono text-xs text-gray-600">{{ $orderCode }}</td>
                        <td class="px-4 py-2">{{ $order->product->name }}</td>
                        <td class="px-4 py-2">{{ $order->vendor->name }}</td>
                        <td class="px-4 py-2">
                            @if ($status === 'dikirim')
                                @if ($isLate)
                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        ⚠ Terlambat
                                    </span>
                                @elseif ($now->diffInMinutes($eta) < 60)
                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        ⏳ Hampir Tiba
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">
                                        🚚 Dikirim
                                    </span>
                                @endif
                            @elseif ($status === 'diterima')
                                @php
                                    $deliveredAt = \Carbon\Carbon::parse($order->tracking->delivered_at);
                                    $isLateDelivered = $deliveredAt->gt($eta);
                                @endphp
                                <span class="inline-block px-2 py-1 text-xs rounded-full {{ $isLateDelivered ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $isLateDelivered ? '⚠ Diterima Terlambat' : '✔ Diterima Tepat Waktu' }}
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600">
                                    {{ ucfirst($status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            {{ $eta->translatedFormat('d F Y H:i') }} WIB
                        </td>
                        <td class="px-4 py-2">
                            @if ($status === 'dikirim')
                                <form method="POST" action="{{ route('customer.orders.confirm', $order->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-600 hover:underline text-sm">
                                        ✔ Konfirmasi Diterima
                                    </button>
                                </form>
                            @elseif ($order->status === 'selesai')
                                <span class="text-green-700 font-semibold text-sm">✔ Selesai</span>
                            @else
                                <span class="text-gray-500 text-sm">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Script Search --}}
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#orderTableBody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
@endif

@endsection
