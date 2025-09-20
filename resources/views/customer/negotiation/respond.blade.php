@extends('layouts.customer')

@section('title', 'Balas Negosiasi')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">💬 Negosiasi Produk: 
        <span class="text-indigo-600">{{ $negotiation->product->name }}</span>
    </h2>

    {{-- 💬 Riwayat Pesan --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6 max-h-96 overflow-y-auto border border-gray-200">
        @forelse($negotiation->messages as $msg)
            <div class="mb-4 px-3 py-2 rounded @if($msg->sender_role === 'vendor') bg-gray-100 @else bg-blue-50 @endif">
                <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
                    <span class="font-semibold capitalize">{{ $msg->sender_role }}</span>
                    <span class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-gray-800 text-sm">{{ $msg->message }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Belum ada pesan dalam negosiasi ini.</p>
        @endforelse
    </div>

    {{-- 💌 Form Kirim Pesan --}}
    <form action="{{ route('customer.negotiation.send', $negotiation->id) }}" method="POST" onsubmit="return validateMessage();">
        @csrf

        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
            <textarea name="message" id="message" rows="3" required
                      class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                      placeholder="Tulis pesan Anda..."></textarea>
        </div>

        <div class="mb-4">
            <label for="offered_price" class="block text-sm font-medium text-gray-700 mb-1">Tawaran Harga (Opsional)</label>
            <input type="number" name="offered_price" id="offered_price"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   placeholder="Misal: 250000">
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                Kirim Pesan
            </button>
        </div>
    </form>

    <script>
        function validateMessage() {
            const message = document.getElementById('message').value.trim();
            if (message === '') {
                alert('Pesan tidak boleh kosong.');
                return false;
            }
            return true;
        }
    </script>
</div>
@endsection
