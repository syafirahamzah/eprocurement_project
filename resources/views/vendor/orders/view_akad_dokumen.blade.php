@extends('layouts.vendor')

@section('title', '📄 Dokumen Akad Kasbon')

@section('content')
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-xl font-semibold mb-4">📄 Dokumen Akad Kasbon</h1>
    
@if($akadDokumen && $akadDokumen->akad_file && $akadDokumen->ktp_path)
    <p><strong>ID Pesanan:</strong> {{ $order->id }}</p>

    <p><strong>Dokumen KTP:</strong></p>
    <img src="{{ asset('storage/' . $akadDokumen->ktp_path) }}" alt="KTP" class="w-48 border mt-2 mb-4">

    <p><strong>File Akad:</strong></p>
    <a href="{{ asset('storage/' . $akadDokumen->akad_file) }}" target="_blank" class="text-blue-500 underline">
        📄 Lihat Dokumen Akad (PDF)
    </a>

    <p class="mt-4"><strong>Status Validasi:</strong> 
        @if($akadDokumen->is_valid)
            ✅ Tervalidasi
        @else
            ❌ Belum Divalidasi
        @endif
    </p>
@else
    <p class="text-red-600">Dokumen akad belum tersedia untuk pesanan ini.</p>
@endif

        

        @if($order->status === 'menunggu konfirmasi')
            <form method="POST" action="{{ route('vendor.orders.approveAkad', $order->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    ✔️ Proses Pesanan
                </button>
            </form>
        @endif

        <div class="mt-6">
            <a href="{{ route('vendor.orders.index') }}" class="inline-block bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">← Kembali</a>
        </div>
    </div>
@endsection
