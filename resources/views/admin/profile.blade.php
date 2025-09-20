@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">👤 Profil Admin</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- TAMPILAN PROFIL --}}
    <div class="bg-white shadow rounded p-6 mb-6">
        <p><strong>Nama:</strong> {{ $admin->name }}</p>
        <p><strong>Email:</strong> {{ $admin->email }}</p>
    </div>

    {{-- FORM EDIT PROFIL --}}
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-semibold mb-4">✏️ Ubah Profil</h2>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                       value="{{ old('name', $admin->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                       value="{{ old('email', $admin->email) }}" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
