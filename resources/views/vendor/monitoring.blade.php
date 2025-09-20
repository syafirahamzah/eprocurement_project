@extends('layouts.vendor')

@section('title', '📦 Monitoring Pengiriman')

@section('content')

    @if($orders->isEmpty())
        <p class="text-gray-600">Belum ada pengiriman aktif.</p>
    @else
        <div class="overflow-x-auto bg-white rounded shadow border">
        <table class="min-w-full table-auto text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 whitespace-nowrap">
                    <tr>
                        <th class="px-4 py-2">Order ID</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Alamat Tujuan</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Estimasi Tiba</th>
                        <th class="px-4 py-2">Progress</th>
                        <th class="px-4 py-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
    $eta = \Carbon\Carbon::parse($order->tracking->estimated_delivery);
    $now = \Carbon\Carbon::now();

    // Gunakan waktu pengiriman sebenarnya, bukan waktu order dibuat
    $start = $order->tracking->shipped_at ?? $order->created_at; 
    $end = $eta;

    $progress = $now->diffInSeconds($start) / max(1, $end->diffInSeconds($start)) * 100;
    $progress = max(0, min(100, round($progress)));

    $status = $order->tracking->status;
    $isLate = $now->greaterThan($eta) && $status !== 'diterima';
@endphp

                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->product->name }}</td>
                            <td class="px-4 py-2">{{ $order->customer->name }}</td>
                            <td class="px-4 py-2">{{ $order->shipping_address ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                @if ($isLate)
                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">
                                        ⚠ Terlambat
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs rounded-full
                                        {{ $status === 'dikirim' ? 'bg-blue-100 text-blue-600' :
                                           ($status === 'dikemas' ? 'bg-yellow-100 text-yellow-800' :
                                           ($status === 'diterima' ? 'bg-green-100 text-green-700' :
                                           'bg-gray-100 text-gray-600')) }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ $eta->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-2 w-60">
                                <div class="w-full bg-gray-200 rounded h-2">
                                    <div class="h-2 rounded 
                                        {{ $isLate ? 'bg-red-500' : 'bg-green-500' }}" 
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>
                                <small class="text-xs text-gray-500">{{ $progress }}% menuju tujuan</small>
                            </td>
                            <td class="px-4 py-2">
                                @if($isLate)
                                    <span class="text-red-600 font-medium">Terlambat dari estimasi</span>
                                @else
                                    <span class="text-gray-500">On time</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
