@extends('layouts.vendor')

@section('title', '🔔 Notifikasi')

@section('content')

    @if($notifications->isEmpty())
        <p class="text-gray-500">Belum ada notifikasi.</p>
    @else
        <div class="bg-white shadow rounded-lg divide-y">
            @foreach($notifications as $notif)
                <div class="px-6 py-4 {{ $notif->is_read ? 'bg-white' : 'bg-yellow-50' }}">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-800">
                            {{ $notif->message }}
                        </p>
                        <form action="{{ route('vendor.notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-indigo-600 text-sm hover:underline">
                                Tandai Dibaca
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
