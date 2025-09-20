@extends('layouts.vendor')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Kelola Status Pengiriman</h1>

    @foreach($trackings as $track)
    <div class="p-4 border rounded mb-6 bg-white shadow">
        <p><strong>Produk:</strong> {{ $track->order->product->name ?? '-' }}</p>
        <p><strong>Customer:</strong> {{ $track->order->customer->name ?? '-' }}</p>
        <p><strong>Status Saat Ini:</strong> {{ $track->status }}</p>

        <form action="{{ route('vendor.tracking.update', $track->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status">Status Pengiriman</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="diproses" {{ $track->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ $track->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="diterima" {{ $track->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    </select>
                </div>

                <div>
                    <label>Estimasi Sampai</label>
                    <input type="date" name="estimated_delivery" class="w-full border rounded p-2" value="{{ $track->estimated_delivery }}">
                </div>

                <div>
                    <label>Tanggal Diterima</label>
                    <input type="date" name="delivered_at" class="w-full border rounded p-2" value="{{ $track->delivered_at }}">
                </div>
            </div>

            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Status</button>
        </form>
    </div>
    @endforeach
</div>
@endsection
