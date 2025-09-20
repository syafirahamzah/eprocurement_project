@extends('layouts.vendor')

@section('title', 'Atur Pengiriman')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6">
    <h2 class="text-xl font-semibold mb-4">🚚 Atur Estimasi Pengiriman</h2>

    <form method="POST" action="{{ route('vendor.orders.updateShipping', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="estimated_delivery" class="block font-medium mb-1">Estimasi Tiba</label>
            <input type="datetime-local" name="estimated_delivery" id="estimated_delivery"
                class="w-full border border-gray-300 px-4 py-2 rounded"
                value="{{ old('estimated_delivery') }}" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Kirim Pesanan
        </button>
    </form>
</div>
@endsection
