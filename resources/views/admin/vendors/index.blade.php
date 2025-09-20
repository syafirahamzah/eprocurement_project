@extends('layouts.admin')

@section('title', 'Data Vendor')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">🏪 Data Vendor</h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full text-sm table-auto border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Telepon</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendors as $vendor)
                <tr>
                    <td class="border px-4 py-2">{{ $vendor->name }}</td>
                    <td class="border px-4 py-2">{{ $vendor->email }}</td>
                    <td class="border px-4 py-2">{{ $vendor->phone ?? '-' }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus vendor ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
