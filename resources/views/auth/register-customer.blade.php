@extends('layouts.blank')

@section('content')
<div class="flex items-center justify-center min-h-screen ">
    <div class="relative bg-white p-8 rounded shadow-md w-full max-w-md">
        <!-- Tombol Close -->
        <button onclick="window.location.href='{{ url('/') }}'"
            class="absolute top-1 right-2 text-gray-600 text-2xl ">
            &times;
        </button>

        <h1 class="text-center text-xl font-semibold mb-6">DAFTAR CUSTOMER</h1>

        <form method="POST" action="{{ route('register.customer') }}">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full px-4 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full px-4 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Tombol -->
            <button type="submit"
                class="w-full bg-darkGreen text-white py-2 rounded font-semibold hover:bg-[#91ac8f] transition">
                Daftar Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
