@extends('layouts.blank')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
<div class="relative bg-white p-8 rounded-md shadow-md w-full max-w-sm">
    <!-- Tombol Close -->
<button onclick="window.location.href='{{ url('/') }}'"
    class="absolute top-1 right-2 text-gray-600 text-xl">
    &times;
</button>

        <h1 class="text-center text-xl font-semibold mb-6">LOGIN</h1>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login.vendor') }}">
            @csrf


            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                           class="w-full pl-8 pr-4 border-b border-black bg-transparent focus:outline-none focus:border-indigo-500 text-sm py-2"
                           placeholder="Masukkan email">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input id="password" type="password" name="password" required
                           class="w-full pl-8 pr-10 border-b border-black bg-transparent focus:outline-none focus:border-indigo-500 text-sm py-2"
                           placeholder="Masukkan password">
                    <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" onclick="togglePassword()">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Tombol Lupa Password -->
<div class="text-sm text-right">
    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
        Lupa password?
    </a>
</div>

        
            <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

            <!-- Tombol Login -->
            <div class="mt-6">
                <button type="submit" class="w-full py-2 bg-darkGreen text-white rounded hover:bg-[#91ac8f] transition font-semibold">
                    LOGIN
                </button>
            </div>

            <!-- Link ke Register -->
            <div class="text-center mt-6 text-sm text-gray-600">
                Tidak punya akun? <a href="{{ route('register.vendor') }}" class="text-indigo-600 hover:underline">Daftar Disini</a>
            </div>
        </form>
    </div>
</div>

<!-- Script Toggle Password -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
