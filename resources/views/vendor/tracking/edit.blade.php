@extends('layouts.vendor')

@section('title', 'Update Pengiriman')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">🚚 Update Pengiriman Order #{{ $order->id }}</h2>

    <form method="POST" action="{{ route('vendor.tracking.update', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="status" class="block font-medium mb-1">Status Pengiriman</label>
            <select name="status" id="status" required class="w-full border rounded px-3 py-2">
                <option value="dikirim" {{ optional($order->tracking)->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="dalam perjalanan" {{ optional($order->tracking)->status == 'dalam perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                <option value="diterima" {{ optional($order->tracking)->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="estimated_delivery" class="block font-medium mb-1">Estimasi Tiba</label>
            <input type="date" name="estimated_delivery" class="w-full border rounded px-3 py-2"
                value="{{ optional($order->tracking)->estimated_delivery ? \Carbon\Carbon::parse($order->tracking->estimated_delivery)->format('Y-m-d') : '' }}">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
