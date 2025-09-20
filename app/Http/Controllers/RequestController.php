<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function show()
    {
        // Ambil vendor yang dipilih dari session
        $selectedVendor = session('selected_vendor');

        // Ambil data vendor berdasarkan ID yang dipilih
        $vendor = User::findOrFail($selectedVendor);

        return view('customer.request', compact('vendor'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Simpan permintaan barang (di sini, misalnya ke database atau ke session untuk sementara)
        // Contoh: 
        // $permintaan = new RequestModel();
        // $permintaan->vendor_id = $request->vendor_id;
        // $permintaan->barang = $request->barang;
        // $permintaan->jumlah = $request->jumlah;
        // $permintaan->keterangan = $request->keterangan;
        // $permintaan->save();

        return redirect()->route('customer.request')->with('status', 'Permintaan berhasil dikirim!');
    }
}
