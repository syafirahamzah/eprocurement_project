@extends('layouts.admin')

@section('title', '📄 Daftar Pesanan')

@section('content')
<h2 class="text-xl font-bold mb-4">📄 Daftar Pesanan</h2>

@if($orders->isEmpty())
    <p class="text-gray-600">Belum ada data pesanan.</p>
@else
    <table class="min-w-full table-auto bg-white rounded shadow border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Kode</th>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2">Customer</th>
                <th class="px-4 py-2">Vendor</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Dipesan Pada</th>
                <th class="px-4 py-2">Selesai Pada</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $order->order_code }}</td>
                    <td class="px-4 py-2">{{ $order->product->name }}</td>
                    <td class="px-4 py-2">{{ $order->customer->name }}</td>
                    <td class="px-4 py-2">{{ $order->vendor->name }}</td>
                    <td class="px-4 py-2">{{ ucfirst($order->status) }}</td>
                    <td class="px-4 py-2">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-2">
                        {{ $order->completed_at ? $order->completed_at->format('d M Y H:i') : '-' }}
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline">🔍 Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
