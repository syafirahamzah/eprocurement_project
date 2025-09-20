@extends('layouts.customer')

@section('title', '📜 Riwayat Pesanan Anda')

@section('content')

@if($orders->isEmpty())
    <p class="text-gray-600">Belum ada riwayat pesanan.</p>
@else
    {{-- Input Pencarian --}}
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="Cari produk, vendor, atau status..." class="border py-2 rounded w-1/4 shadow">
    </div>

    {{-- Tabel Riwayat --}}
    <div class="overflow-x-auto bg-white rounded shadow border">
        <table class="min-w-full table-auto text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Produk</th>
                    <th class="px-4 py-2">Vendor</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Dipesan</th>
                    <th class="px-4 py-2">Estimasi Tiba</th>
                    <th class="px-4 py-2">Diterima</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                @foreach($orders as $order)
                    @php
                        $tracking = $order->tracking;
                        $eta = $tracking?->estimated_delivery ? \Carbon\Carbon::parse($tracking->estimated_delivery)->translatedFormat('d M Y H:i') : '-';
                        $created = $order->created_at ? $order->created_at->translatedFormat('d M Y H:i') : '-';
                        $received = ($order->status === 'selesai' && $tracking) ? $tracking->updated_at->translatedFormat('d M Y H:i') : '-';
                        $orderCode = $order->order_code ?? 'ORD-' . $order->id;
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2 font-mono text-xs text-gray-600">{{ $orderCode }}</td>
                        <td class="px-4 py-2">{{ $order->product->name }}</td>
                        <td class="px-4 py-2">{{ $order->vendor->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            @if($order->status === 'selesai')
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 text-xs rounded-full">✔ Selesai</span>
                            @elseif($order->status === 'dikirim')
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 text-xs rounded-full">🚚 Dikirim</span>
                            @else
                                <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 text-xs rounded-full">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $created }}</td>
                        <td class="px-4 py-2">{{ $eta }}</td>
                        <td class="px-4 py-2">{{ $received }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Script Search Filter --}}
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
