@if ($errors->any())
    <div class="mb-4 text-red-500">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf

    <div class="mb-4">
        <label for="name" class="block text-sm font-semibold">Nama</label>
        <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm font-semibold">Email</label>
        <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
    </div>

    <div class="mb-4">
        <label for="password" class="block text-sm font-semibold">Password</label>
        <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
    </div>

    <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-semibold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
    </div>

    <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Daftar</button>
</form>
