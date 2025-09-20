@extends('layouts.blank')

@section('content')
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl relative">
        <!-- Tombol Close -->
        <button onclick="window.history.back();" class="absolute top-1 right-2 text-gray-600 hover:text-gray-800 text-2xl">
            &times;
        </button>

        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Forgot Password</h2>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    class="mt-2 block w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">

                @error('email')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button 
                    type="submit" 
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200">
                    Send Password Reset Link
                </button>
            </div>

            <div class="flex justify-between text-sm text-indigo-600">
                <a href="{{ route('register') }}" class="hover:text-indigo-800">Don't have an account? Sign up</a>
            </div>
        </form>
    </div>
</div>
@endsection
