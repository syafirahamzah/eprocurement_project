@extends('layouts.vendor')

@section('title', '📜 Riwayat Pesanan')

@section('content')
<div class="w-full">
    <h2 class="text-xl font-semibold mb-4">Riwayat Pesanan</h2>

    {{-- Filter dan Search --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari produk atau customer..." class="px-3 py-2 border rounded w-64">

        <select name="status" class="px-3 py-2 border rounded">
            <option value="">Semua Status</option>
            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">🔍 Filter</button>
    </form>

    {{-- Tabel Riwayat --}}
    @if($orders->isEmpty())
        <p class="text-gray-600">Belum ada riwayat pesanan.</p>
    @else
        <div class="overflow-x-auto bg-white rounded shadow border">
            <table class="min-w-full table-auto text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Order ID</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Estimasi Tiba</th>
                        <th class="px-4 py-2">Terkirim Pada</th>
                        <th class="px-4 py-2">Struk</th>
                        <th class="px-4 py-2">Kontrak</th>
                        <th class="px-4 py-2">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $tracking = $order->tracking;
                            $eta = $tracking?->estimated_delivery ? \Carbon\Carbon::parse($tracking->estimated_delivery)->translatedFormat('d M Y H:i') : '-';
                            $deliveredAt = $tracking?->updated_at ? \Carbon\Carbon::parse($tracking->updated_at)->translatedFormat('d M Y H:i') : '-';
                            $akad = \App\Models\AkadDokumen::where('order_id', $order->id)->first();
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2 font-mono text-gray-700">#{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->product->name }}</td>
                            <td class="px-4 py-2">{{ $order->customer->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if($order->status === 'selesai')
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 text-xs rounded-full">✔ Selesai</span>
                                @elseif($order->status === 'dikirim')
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 text-xs rounded-full">🚚 Dikirim</span>
                                @elseif($order->status === 'diproses')
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 text-xs rounded-full">⏳ Diproses</span>
                                @else
                                    <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 text-xs rounded-full">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $eta }}</td>
                            <td class="px-4 py-2">{{ $deliveredAt }}</td>

                            {{-- Struk --}}
                            <td class="px-4 py-2">
                                @if(in_array($order->status, ['dikirim', 'selesai']))
                                    <a href="{{ route('vendor.orders.invoice', $order->id) }}" target="_blank" class="text-blue-600 underline text-sm">🧾 Struk</a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

<td class="px-6 py-4 space-y-1 text-xs">
                    @if($order->status === 'dikirim' || $order->status === 'dikirim')
                        <a href="{{ route('vendor.orders.akad', $order->id) }}"
                           class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 block text-center">
                            Download Invoice
                        </a>
                    @endif

                            {{-- Pembayaran --}}
                            <td class="px-4 py-2">
                                @if($order->payment_status === 'lunas')
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Lunas</span>
                                @else
                                    <form action="{{ route('vendor.orders.markPaid', $order->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai sudah dibayar?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-yellow-200 text-yellow-900 text-xs px-2 py-1 rounded-full hover:bg-yellow-300">
                                            Tandai Sudah Dibayar
                                        </button>
                                    </form>
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
