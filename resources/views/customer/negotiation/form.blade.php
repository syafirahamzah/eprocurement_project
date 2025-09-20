@extends('layouts.customer')

@section('title', 'Form Negosiasi')

@section('content')
<div class="w-full bg-white px-6 py-6 rounded shadow">

    {{-- Judul Produk --}}
    <h2 class="text-xl font-bold mb-4">💬 Negosiasi Produk:
        <span class="text-indigo-700">{{ $product->name }}</span>
    </h2>

    {{-- Harga Produk --}}
    <div class="mb-4 text-sm text-gray-600">
        Harga Produk Asli:
        <span class="font-semibold text-red-600">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </span><br>
        @if ($negotiation->final_price)
        Harga yang Ditawarkan Vendor:
        <span class="font-semibold text-green-600" id="unit-price" data-price="{{ $negotiation->final_price }}">
            Rp {{ number_format($negotiation->final_price, 0, ',', '.') }}
        </span>
        @endif
    </div>

    {{-- Riwayat Chat --}}
    <div id="chat-box" class="border rounded p-4 bg-gray-50 h-64 overflow-y-auto mb-4 space-y-3">
        @forelse ($messages as $msg)
            @if($msg->sender_role === 'customer')
                <div class="flex justify-end">
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg shadow text-sm max-w-xs">
                        <strong>Anda:</strong> {{ $msg->message }}
                        <div class="text-[11px] text-right text-gray-500 mt-1">
                            {{ $msg->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="flex justify-start">
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow text-sm max-w-xs">
                        <strong>Vendor:</strong> {{ $msg->message }}
                        <div class="text-[11px] text-gray-500 mt-1">
                            {{ $msg->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-gray-500 text-sm">Belum ada pesan dalam negosiasi ini.</p>
        @endforelse
    </div>

    {{-- Form Balas Pesan --}}
    <form action="{{ route('customer.negotiation.respond', $negotiation->id) }}" method="POST" class="space-y-3">
        @csrf
        <label class="block text-sm font-medium text-gray-700">Ketik Balasan</label>
        <textarea name="message" rows="3" class="w-full p-3 border rounded text-sm"
                  placeholder="Contoh: Bisa diskon kalau beli banyak?" required></textarea>

        <div class="flex justify-between items-center mt-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                Kirim 
            </button>
            <a href="{{ route('customer.eCatalog') }}" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded hover:bg-gray-300">
                ← Kembali ke Katalog
            </a>
        </div>
    </form>

    {{-- Status Negosiasi --}}
    <div class="text-sm bg-gray-100 p-3 rounded border mt-4">
        <p>Status negosiasi: <strong>{{ $negotiation->status }}</strong></p>
        <p>Order ID: <strong>{{ $negotiation->order_id ?? 'Belum ada' }}</strong></p>
    </div>

    {{-- Form Persetujuan & Pemesanan --}}
    @if ($negotiation->status === 'disetujui' && !$negotiation->order)
        <form action="{{ route('customer.negotiation.agree', $negotiation->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="negotiation_id" value="{{ $negotiation->id }}">


            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah Pesanan</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-32 border p-2 rounded text-sm" required>

            <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="w-full border p-2 rounded text-sm" required>
                <option value="">-- Pilih Metode --</option>
                <option value="akad">Bayar saat Akad (Kasbon)</option>
                <option value="cod">Bayar di Tempat (COD)</option>
            </select>

            <div id="statement-box" class="hidden bg-yellow-50 border border-yellow-300 p-4 text-sm rounded"></div>

            <div id="agreementSection" class="mb-4 hidden border border-gray-200 p-3 rounded text-sm text-gray-700">
                <p class="mb-2 font-semibold">📝 Persetujuan Akad:</p>
                <div class="h-32 overflow-y-auto border border-gray-100 p-2 bg-gray-50 rounded">
                    <p>Saya dengan ini menyetujui bahwa pembayaran akan dilakukan saat akad, dan saya bertanggung jawab atas pembayaran sesuai harga dan ketentuan yang telah disepakati. Ketentuan ini berlaku sah secara internal sistem e-procurement.</p>
                </div>
                <label class="flex items-center mt-2">
                    <input type="checkbox" name="agreement_check" id="agreementCheck" class="mr-2"> Saya menyetujui akad di atas.
                </label>
            </div>

            <div id="ktpSection" class="mb-4 hidden">
                <label for="ktp_document" class="block text-sm mb-1">Upload KTP Anda</label>
                <input type="file" name="ktp_document" id="ktp_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded">
            </div>

            <div id="agreementUploadSection" class="mb-4 hidden">
                <label for="akad_file" class="block text-sm mb-1">Upload Dokumen Akad (PDF)</label>
                <input type="file" name="akad_file" id="akad_file" accept=".pdf" class="w-full border rounded">
            </div>

            <p class="text-sm text-gray-700">Total Harga: <span id="total-price" class="font-semibold text-green-600">Rp 0</span></p>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="agree_check" required>
                <span class="text-sm">Saya menyetujui pernyataan di atas</span>
            </label>

            <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 text-sm">
                Setujui Harga & Buat Pesanan
            </button>
        </form>
    @elseif ($negotiation->status === 'disetujui')
        <div class="mt-6 text-green-600 font-medium">
            Pesanan telah dibuat berdasarkan negosiasi ini.
        </div>
    @endif
</div>

<script>
    window.onload = function () {
        const chatBox = document.getElementById('chat-box');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

        const quantityInput = document.getElementById('quantity');
        const totalPriceSpan = document.getElementById('total-price');
        const unitPrice = parseInt(document.getElementById('unit-price')?.dataset.price || 0);

        function updateTotal() {
            const qty = parseInt(quantityInput?.value || 1);
            const total = unitPrice * qty;
            totalPriceSpan.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', updateTotal);
            updateTotal();
        }

        const paymentSelect = document.getElementById('payment_method');
        const statementBox = document.getElementById('statement-box');
        const agreementSection = document.getElementById('agreementSection');
        const ktpSection = document.getElementById('ktpSection');
        const akadSection = document.getElementById('agreementUploadSection');

        paymentSelect.addEventListener('change', function () {
            const value = this.value;
            if (value === 'akad') {
                statementBox.classList.remove('hidden');
                statementBox.innerHTML = "Dengan menyetujui harga ini, saya menyatakan bersedia membayar langsung saat akad serah terima dengan vendor. Jika tidak dilakukan, saya siap menanggung sanksi sesuai ketentuan.";
                agreementSection.classList.remove('hidden');
                ktpSection.classList.remove('hidden');
                akadSection.classList.remove('hidden');
            } else if (value === 'cod') {
                statementBox.classList.remove('hidden');
                statementBox.innerHTML = "Dengan menyetujui harga ini, saya menyatakan bersedia membayar secara tunai di lokasi saat barang diterima dari vendor. Saya bertanggung jawab penuh atas pembayaran dan bersedia dikenakan sanksi jika tidak melakukan pembayaran saat itu.";
                agreementSection.classList.add('hidden');
                ktpSection.classList.add('hidden');
                akadSection.classList.add('hidden');
            } else {
                statementBox.classList.add('hidden');
                agreementSection.classList.add('hidden');
                ktpSection.classList.add('hidden');
                akadSection.classList.add('hidden');
                statementBox.innerHTML = "";
            }
        });
    };
</script>
@endsection
