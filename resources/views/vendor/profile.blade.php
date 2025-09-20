@extends('layouts.vendor')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Vendor</h1>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded text-green-700 bg-green-100 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tampilan profil --}}
    <div id="profile-view">
        <div class="flex items-center space-x-6 mb-6">
            {{-- Foto Profil (inisial huruf nama) --}}
            <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center text-3xl text-white font-bold uppercase">
                {{ strtoupper(substr($vendor->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $vendor->name }}</h2>
                <p class="text-sm text-gray-500">Bergabung sejak: {{ $vendor->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-600">Email</dt>
                <dd class="text-gray-900">{{ $vendor->email }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-600">Nomor HP</dt>
                <dd class="text-gray-900">{{ $vendor->phone_number ?? '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-600">Alamat</dt>
                <dd class="text-gray-900">{{ $vendor->address ?? '-' }}</dd>
             </div>
             <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-600">Kategori Vendor</dt>
                <dd class="text-gray-900">{{ $vendor->category ?? '-' }}</dd>
             </div>
        </dl>

        <div class="mt-6 text-right">
            <button onclick="toggleEdit()" class="px-4 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                Edit Profil
            </button>
        </div>
    </div>

    {{-- Form edit (toggle) --}}
    <div id="profile-edit" class="hidden mt-10">
        <form method="POST" action="{{ route('vendor.profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}"
                       class="w-full border p-2 rounded shadow-sm">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}"
                       class="w-full border p-2 rounded shadow-sm">
            </div>

            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $vendor->phone_number) }}"
                       class="w-full border p-2 rounded shadow-sm">
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" rows="3"
                          class="w-full border p-2 rounded shadow-sm">{{ old('address', $vendor->address) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Kategori Vendor</label>
                <input type="text" name="category" value="{{ old('category', $vendor->category) }}" class="w-full border p-2 rounded shadow-sm">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleEdit()" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Form ubah password --}}
    <div class="mt-16 border-t pt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ubah Password</h2>

        @if (session('password_success'))
            <div class="mb-4 p-4 rounded text-green-700 bg-green-100 border border-green-300">
                {{ session('password_success') }}
            </div>
        @endif

        @if ($errors->has('current_password') || $errors->has('new_password'))
            <div class="mb-4 p-4 rounded text-red-700 bg-red-100 border border-red-300">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        @if (str_contains($error, 'password'))
                            <li>{{ $error }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('vendor.profile.updatePassword') }}" class="space-y-4 max-w-lg">
            @csrf
            @method('PATCH')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Password Lama</label>
                <input type="password" name="current_password" id="current_password" required class="w-full border p-2 rounded shadow-sm">
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="new_password" id="new_password" required class="w-full border p-2 rounded shadow-sm">
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="w-full border p-2 rounded shadow-sm">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Ubah Password</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleEdit() {
    document.getElementById('profile-view').classList.toggle('hidden');
    document.getElementById('profile-edit').classList.toggle('hidden');
}
</script>
@endsection
