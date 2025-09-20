@extends('layouts.vendor')

@section('title', 'Balas Negosiasi')

@section('content')
<div class="w-full bg-white px-6 py-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        💬 Negosiasi Produk: <span class="text-indigo-600">{{ $product->name }}</span>
    </h2>

    {{-- 💬 Riwayat Pesan --}}
    <div id="chat-box" class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6 max-h-[28rem] overflow-y-auto space-y-4">
        @forelse($messages as $msg)
            @if($msg->sender_role === 'vendor')
                <div class="flex justify-end">
                    <div class="bg-green-100 text-green-900 px-5 py-3 rounded-xl shadow max-w-[70%]">
                        <div class="text-sm">
                            <strong>Anda (Vendor):</strong> {{ $msg->message }}
                        </div>
                        <div class="text-xs text-right text-gray-500 mt-1">
                            {{ $msg->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="flex justify-start">
                    <div class="bg-blue-100 text-blue-900 px-5 py-3 rounded-xl shadow max-w-[70%]">
                        <div class="text-sm">
                            <strong>Customer:</strong> {{ $msg->message }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $msg->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-center text-gray-500 text-sm">Belum ada pesan dalam negosiasi ini.</p>
        @endforelse
    </div>

    {{-- 📝 Form Balas Pesan --}}
    <form method="POST" action="{{ route('vendor.negotiation.respond', $negotiation->id) }}" class="bg-white p-6 rounded-lg shadow-md mb-6 border">
        @csrf
        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">✉️ Tulis Balasan</label>
            <textarea name="message" rows="3"
                class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                placeholder="Contoh: Bisa kami berikan harga Rp60.000 jika beli 3." required></textarea>
        </div>
        <div class="text-right">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                📩 Kirim 
            </button>
        </div>
    </form>

    {{-- Penetapan Harga Final --}}
    @if (isset($negotiation) && $negotiation->status === 'menunggu')
        <form action="{{ route('vendor.negotiation.approve', $negotiation->id) }}" method="POST" class="bg-green-50 border border-green-200 p-6 rounded-lg shadow-md mb-6">
            @csrf
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                <input type="number" name="offered_price"
                    value="{{ old('offered_price', $negotiation->offered_price ?? '') }}"
                    placeholder="Harga final (Rp)" required
                    class="w-full md:w-64 border px-4 py-2 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm" />
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                    Setujui Penawaran
                </button>
            </div>
        </form>
    @endif

    {{-- 🔙 Kembali --}}
    <div class="mt-4">
        <a href="{{ route('vendor.negotiation.index') }}"
            class="inline-block bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 shadow transition text-sm">
            ← Kembali ke Daftar Negosiasi
        </a>
    </div>
</div>

{{-- 🔽 Auto Scroll --}}
<script>
    window.onload = function () {
        const chatBox = document.getElementById('chat-box');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    };
</script>
@endsection
