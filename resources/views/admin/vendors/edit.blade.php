@extends('layouts.admin')

@section('title', 'Edit Vendor')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">✏️ Edit Vendor</h2>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-800 px-4 py-2 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="name" value="{{ old('name', $vendor->name) }}"
                   class="w-full border px-3 py-2 rounded focus:outline-none focus:ring">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $vendor->email) }}"
                   class="w-full border px-3 py-2 rounded focus:outline-none focus:ring">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}"
                   class="w-full border px-3 py-2 rounded focus:outline-none focus:ring">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Alamat</label>
            <input type="text" name="address" value="{{ old('address', $vendor->address) }}"
                   class="w-full border px-3 py-2 rounded focus:outline-none focus:ring">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
