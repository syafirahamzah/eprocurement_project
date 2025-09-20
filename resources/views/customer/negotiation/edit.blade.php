@extends('layouts.customer')

@section('title', 'Edit Pesan Negosiasi')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">✏️ Edit Pesan Negosiasi</h2>

    <form action="{{ route('customer.negotiation.update', $message->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
            <textarea name="message" rows="4"
                class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required>{{ old('message', $message->message) }}</textarea>
        </div>

        <div class="flex justify-between items-center">
            {{-- Tombol Kembali --}}
            @if ($message->negotiation && $message->negotiation->product_id)
                <a href="{{ route('customer.negotiation.form', $message->negotiation->product_id) }}"
                   class="text-gray-600 hover:underline">⬅️ Kembali</a>
            @else
                <span class="text-sm text-red-500">Negosiasi tidak ditemukan</span>
            @endif

            {{-- Tombol Submit --}}
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
