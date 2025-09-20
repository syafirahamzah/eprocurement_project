@extends('layouts.admin')

@section('title', 'Data Customer')

@section('content')
<div class="px-6 py-4">
    <h1 class="text-2xl font-bold mb-6">👥 Data Customer</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded shadow border">
        <table class="min-w-full table-auto text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Alamat</th>
                    <th class="px-6 py-3">No. Telepon</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse($customers as $index => $customer)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $index + 1 }}</td>
                        <td class="px-6 py-3">{{ $customer->name }}</td>
                        <td class="px-6 py-3">{{ $customer->email }}</td>
                        <td class="px-6 py-3">{{ $customer->address ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-6 py-3 space-x-2">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-blue-600 hover:underline">✏️ Edit</a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data customer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
