@extends('layouts.vendor')

@section('title', 'Permintaan Masuk')

@section('content')
<h2 class="text-2xl font-semibold mb-4">Permintaan dari Customer</h2>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3">Produk</th>
            <th class="p-3">Jumlah</th>
            <th class="p-3">Customer</th>
            <th class="p-3">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $req)
        <tr class="border-t">
            <td class="p-3">{{ $req->product->name }}</td>
            <td class="p-3">{{ $req->quantity }}</td>
            <td class="p-3">{{ $req->customer->name }}</td>
            <td class="p-3">
                <a href="{{ route('vendor.requests.show', $req->id) }}" class="text-blue-600">Lihat</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
