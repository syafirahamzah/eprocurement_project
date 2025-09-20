@extends('layouts.customer')

@section('title', 'Detail Negosiasi')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">💬 Detail Negosiasi</h2>

    <div class="mb-4">
        <p><strong>Produk:</strong> {{ $negotiation->product->name }}</p>
        <p><strong>Jumlah:</strong> {{ $negotiation->quantity }} {{ $negotiation->product->unit }}</p>
        <p><strong>Harga yang Disepakati:</strong> Rp {{ number_format($negotiation->final_price, 0, ',', '.') }}</p>
        <p><strong>Vendor:</strong> {{ $negotiation->vendor->name }}</p>
    </div>

    @if($negotiation->status === 'disetujui')
        <div class="text-green-600 font-semibold mb-4">Negosiasi sudah disetujui. Pesanan telah dibuat.</div>
    @else
        <form action="{{ route('negotiation.agree', $negotiation->id) }}" method="POST">
            @csrf

            <div class="bg-yellow-50 border border-yellow-300 p-4 text-sm rounded mb-3">
                Dengan menyetujui harga ini, saya menyatakan bersedia membayar langsung saat akad serah terima dengan vendor.
                Jika tidak dilakukan, saya siap menanggung sanksi sesuai ketentuan.
            </div>

            <label class="flex items-center gap-2 mb-2">
                <input type="checkbox" name="agree_check" required>
                <span class="text-sm">Saya menyetujui pernyataan di atas</span>
            </label>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                 Setujui Harga & Buat Pesanan
            </button>
        </form>
    @endif
</div>
@endsection
