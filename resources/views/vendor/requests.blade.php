@extends('layouts.vendor')

@section('title', 'Daftar Permintaan')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Daftar Permintaan</h2>

    @if ($requests->count() > 0)
        <table class="min-w-full text-sm text-left border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Produk</th>
                    <th class="p-3 border">Jumlah</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $index => $req)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-3 border">{{ $index + 1 }}</td>
                    <td class="p-3 border">{{ $req->product->name ?? '-' }}</td>
                    <td class="p-3 border">{{ $req->quantity }} {{ $req->unit }}</td>
                    <td class="p-3 border">{{ ucfirst($req->status) }}</td>
                    <td class="p-3 border">{{ $req->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    @else
        <p class="text-gray-500">Belum ada permintaan.</p>
    @endif
</div>
@endsection
