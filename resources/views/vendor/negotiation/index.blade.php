@extends('layouts.vendor')

@section('title', '📦 Daftar Permintaan Negosiasi')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 border border-green-200 shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg shadow border bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 font-semibold text-gray-700 text-left">
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Harga Awal</th>
                    <th class="px-6 py-3">Harga Nego</th>
                    <th class="px-6 py-3">Tipe Pemesanan</th>
                    <th class="px-6 py-3">Status Negosiasi</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($negotiations as $negotiation)
                    <tr class="border-t">
                        <td class="px-6 py-4 text-red-500">
                            {{ $negotiation->order?->id ?? 'order null' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $negotiation->product?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $negotiation->customer?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            Rp {{ number_format($negotiation->product?->price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            Rp {{ number_format($negotiation->offered_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full font-medium">
                                Negosiasi
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ 
                                    $negotiation->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' :
                                    ($negotiation->status == 'disetujui' ? 'bg-green-100 text-green-700' :
                                    'bg-red-100 text-red-700') 
                                }}">
                                {{ ucfirst($negotiation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(in_array(strtolower($negotiation->status), ['pending', 'menunggu']))
                                <div class="flex flex-col items-center gap-2">
                                    <div class="flex gap-2">
                                        <form action="{{ route('vendor.negotiation.approve', $negotiation->id) }}" method="POST">
    @csrf
    <input type="hidden" name="offered_price" value="{{ $negotiation->offered_price ?? 1000 }}">
    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
        ✔ Setujui
    </button>
</form>


                                        <form action="{{ route('vendor.negotiation.reject', $negotiation->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                                ✖ Tolak
                                            </button>
                                        </form>
                                    </div>
                                    <a href="{{ route('vendor.negotiation.form', $negotiation->product_id) }}"
                                       class="text-indigo-600 hover:underline text-sm">
                                        💬 Lihat Chat
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-gray-500">Status: {{ ucfirst($negotiation->status) }}</span>
                                    <a href="{{ route('vendor.negotiation.form', $negotiation->product_id) }}"
                                       class="text-indigo-600 hover:underline text-sm">
                                        💬 Lihat Chat
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
