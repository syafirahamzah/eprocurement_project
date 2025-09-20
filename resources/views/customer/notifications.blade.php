@extends('layouts.customer')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-5xl mx-auto p-6">


    @if($notifications->isEmpty())
        <div class="p-4 bg-yellow-100 text-yellow-800 border rounded">
            Tidak ada notifikasi untuk saat ini.
        </div>
    @else
        @foreach($notifications as $notification)
            <div class="bg-white rounded-lg shadow p-4 mb-4 border">
                {{-- Judul dan Isi --}}
                <p class="font-semibold text-lg">📝 {{ $notification->title }}</p>
                <p class="text-gray-700 mb-2">{{ $notification->message }}</p>

                {{-- Waktu --}}
                <p class="text-sm text-gray-500 mb-2">
                    ⏱ {{ $notification->created_at->diffForHumans() }}
                </p>

                {{-- Status --}}
                <p class="mb-2">
                    <strong>Status:</strong>
                    @if($notification->is_read)
                        <span class="text-green-600">✅ Sudah Dibaca</span>
                    @else
                        <span class="text-red-600">📩 Belum Dibaca</span>
                    @endif
                </p>

                {{-- Tombol Aksi --}}
                <div class="flex flex-wrap gap-3 mt-3 items-center">
                    {{-- Tombol Tandai Dibaca --}}
                    @if(!$notification->is_read)
                        <form action="{{ route('customer.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-green-600 hover:underline">
                                ✅ Tandai Dibaca
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Balas Chat --}}
  @if($notification->type === 'negotiation' && $notification->product_id)
    <a href="{{ route('customer.negotiation.form', $notification->product_id) }}" class="text-blue-600 hover:underline">
        🔁 Balas Chat
    </a>
@endif



                    {{-- Tombol Pesan Sekarang --}}
                    @if(Str::contains(strtolower($notification->title), 'disetujui') && $notification->related_id)
                        <a href="{{ route('customer.negotiation.form', $notification->related_id) }}"
                           class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            📦 Pesan Sekarang
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
