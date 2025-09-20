@extends('admin.layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold text-center mb-6">Login Admin</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block mb-1">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full p-2 border rounded focus:outline-none focus:ring" required autofocus>
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-1">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full p-2 border rounded focus:outline-none focus:ring" required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
