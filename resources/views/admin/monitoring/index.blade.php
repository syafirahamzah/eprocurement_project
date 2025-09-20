@extends('layouts.admin')

@section('title', '📊 Monitoring Seluruh Pesanan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-6">📊 Monitoring Seluruh Pesanan</h1>

    @if($orders->isEmpty())
        <p class="text-gray-600">Belum ada data pesanan.</p>
    @else
        <div class="overflow-x-auto bg-white rounded shadow border">
            <table class="min-w-full table-auto text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Kode Pesanan</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Vendor</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Dipesan Pada</th>
                        <th class="px-4 py-2">Selesai Pada</th>
                        <th class="px-4 py-2">Estimasi Tiba</th>
                        <th class="px-4 py-2">Update Terakhir</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $tracking = $order->tracking;
                            $eta = $tracking?->estimated_delivery 
                                ? \Carbon\Carbon::parse($tracking->estimated_delivery)->translatedFormat('d M Y H:i')
                                : '-';
                            $updated = $tracking?->updated_at 
                                ? \Carbon\Carbon::parse($tracking->updated_at)->translatedFormat('d M Y H:i')
                                : '-';

                            $created = $order->created_at 
                                ? $order->created_at->translatedFormat('d M Y H:i') 
                                : '-';

                            $completed = $order->completed_at 
                                ? \Carbon\Carbon::parse($order->completed_at)->translatedFormat('d M Y H:i') 
                                : '-';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2 font-mono">{{ $order->order_code ?? 'ORD-' . $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->product->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->customer->name ?? '-' }}</td>
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
                            <td class="px-4 py-2">{{ $completed }}</td>
                            <td class="px-4 py-2">{{ $eta }}</td>
                            <td class="px-4 py-2">{{ $updated }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline text-sm">🔍 Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
