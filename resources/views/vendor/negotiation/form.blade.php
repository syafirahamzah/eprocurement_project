@extends('layouts.vendor')

@section('title', 'Negosiasi Produk')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Negosiasi Produk: {{ $negotiation->product->name }}</h2>

    {{-- 💬 Riwayat Pesan --}}
    <div class="border p-4 rounded h-96 overflow-y-auto bg-gray-50">
        @forelse ($messages as $msg)
            <div class="mb-4">
                <div class="text-sm text-gray-600 font-medium">
                    {{ ucfirst($msg->sender_role) }}:
                </div>
                <div class="p-3 rounded-md mt-1 {{ $msg->sender_role === 'vendor' ? 'bg-green-100' : 'bg-blue-100' }}">
                    {{ $msg->message }}
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ $msg->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Belum ada pesan.</p>
        @endforelse
    </div>

    {{-- ✏️ Kirim Pesan Biasa --}}
    <form action="{{ route('vendor.negotiation.respond', $negotiation->id) }}" method="POST" class="mt-6 space-y-2">
        @csrf
        <label class="block text-sm font-medium text-gray-700">Kirim Pesan</label>
        <textarea name="message" rows="3" class="w-full p-2 border rounded" placeholder="Tulis pesan biasa..."></textarea>
        <button type="submit" class="mt-2 px-4 py-2 bg-[#4B5945] text-white rounded hover:bg-[#5c6d50]">
            Kirim
        </button>
    </form>

    <hr class="my-6">

    {{-- 💰 Kirim Penawaran Harga Final --}}
    <form method="POST" action="{{ route('vendor.negotiation.offer', $negotiation->id) }}" class="space-y-3">
        @csrf
        <div>
            <label class="block font-medium">Harga Final (Rp)</label>
            <input type="number" name="final_price" required class="w-full p-2 border rounded" placeholder="Contoh: 55000">
        </div>

        <div>
            <label class="block font-medium">Pesan Penawaran</label>
            <textarea name="message" required class="w-full p-2 border rounded" placeholder="Contoh: Kami bisa kasih harga spesial jika pembelian di atas 5 item."></textarea>
        </div>

        <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700">
            Kirim Penawaran Harga
        </button>
    </form>

    {{--onfirmasi Harga Jika Sudah Deal --}}
    @if (!$negotiation->is_approved && $negotiation->final_price)
        <hr class="my-6">
        <form action="{{ route('vendor.negotiation.approve', $negotiation->id) }}" method="POST" class="space-y-4">
            @csrf
            <p class="font-semibold">Harga Final yang akan disetujui:</p>
            <input type="number" name="final_price" value="{{ $negotiation->final_price }}" readonly class="w-full p-2 border rounded bg-gray-100">

            <div>
                <label class="block font-medium">Estimasi Tiba</label>
                <input type="date" name="estimated_delivery" required class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Setujui Harga & Lanjutkan
            </button>
        </form>
    @elseif ($negotiation->is_approved)
        <div class="mt-6 p-3 bg-green-100 border rounded text-green-800 font-medium">
            Harga sudah disetujui: Rp {{ number_format($negotiation->final_price, 0, ',', '.') }}
        </div>
    @endif
</div>
@endsection
