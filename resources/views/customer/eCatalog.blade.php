@extends('layouts.customer')

@section('title', 'E-Katalog')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">🧱 Produk</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($products as $product)
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold text-indigo-800">{{ $product->name }}</h2>
            <p class="text-sm text-gray-600">Vendor: <span class="text-indigo-600">{{ $product->vendor->name }}</span></p>
            <p class="text-green-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <div class="flex flex-col gap-2 mt-4">
                <a href="{{ route('customer.negotiation.form', $product->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-center shadow">
                    💬 Ajukan Negosiasi
                </a>

                <button onclick="openOrderModal({{ $product->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    🛒 Pesan Sekarang
                </button>

                <button onclick="openDetailModal({{ $product->id }})" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow">
                    🔍 Detail Produk
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Modal Pemesanan --}}
<div id="orderModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-md shadow-lg p-6 relative">
        <button onclick="closeOrderModal()" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-xl">×</button>
        <h2 class="text-xl font-bold mb-4" id="orderProductName">Pesan Produk</h2>

        <form id="orderForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="orderProductId">
            <input type="hidden" name="total_price" id="hiddenTotalPrice">

            <div class="mb-2">
                <label class="block text-sm text-gray-600 mb-1">Harga Satuan:</label>
                <div id="orderUnitPrice" class="font-semibold text-green-700">Rp -</div>
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-sm text-gray-600 mb-1">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1" class="w-full border border-gray-300 px-3 py-2 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="payment_method" class="block text-sm text-gray-600 mb-1">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-indigo-200" onchange="toggleAgreementFields(this.value)">
                    <option value="cod">Bayar di Tempat</option>
                    <option value="akad">Bayar saat Akad/kasbon</option>
                </select>
            </div>

            {{-- Bagian Akad --}}
            <div id="agreementSection" class="mb-4 hidden border border-gray-200 p-3 rounded text-sm text-gray-700">
                <p class="mb-2 font-semibold">📝 Persetujuan Akad:</p>
                <div class="h-32 overflow-y-auto border border-gray-100 p-2 bg-gray-50 rounded">
                    <p>Saya dengan ini menyetujui bahwa pembayaran akan dilakukan saat akad, dan saya bertanggung jawab atas pembayaran sesuai harga dan ketentuan yang telah disepakati. Ketentuan ini berlaku sah secara internal sistem e-procurement.</p>
                </div>
                <label class="flex items-center mt-2">
                    <input type="checkbox" id="agreementCheck" class="mr-2"> Saya menyetujui akad di atas.
                </label>
            </div>

            <div id="ktpSection" class="mb-4 hidden">
                <label for="ktp_document" class="block text-sm mb-1">Upload KTP Anda</label>
                <input type="file" name="ktp_document" id="ktp_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded">
            </div>

            <div id="agreementUploadSection" class="mb-4 hidden">
                <label for="agreement_document" class="block text-sm mb-1">Upload Dokumen Akad (PDF)</label>
                <input type="file" name="akad_file" id="akad_file" accept=".pdf" class="w-full border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Total Harga:</label>
                <div id="orderTotalPrice" class="font-bold text-xl text-indigo-700">Rp -</div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeOrderModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded">Batal</button>
                <button type="submit" onclick="return confirmAgreement()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Konfirmasi</button>
            </div>
            
        </form>
    </div>
</div>

{{-- Modal Detail Produk --}}
<div id="detailModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-md shadow-lg p-6 relative">
        <button onclick="closeDetailModal()" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-xl">×</button>
        <h2 class="text-xl font-bold mb-4" id="detailProductName">Detail Produk</h2>
        <div class="mb-2 text-sm text-gray-600" id="detailVendor">Vendor: -</div>
        <div class="mb-2 text-green-700 font-semibold text-lg" id="detailProductPrice">Rp -</div>
        <div class="mb-4 text-gray-700 text-sm leading-relaxed" id="detailProductDescription">-</div>
        <button onclick="closeDetailModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded mt-4 w-full">Tutup</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const productData = @json($products);

    function openOrderModal(id) {
        const product = productData.find(p => p.id === id);
        if (!product) return;

        const harga = product.price;

        document.getElementById('orderProductName').textContent = 'Pesan: ' + product.name;
        document.getElementById('orderUnitPrice').textContent = `Rp ${Number(harga).toLocaleString('id-ID')}`;
        document.getElementById('quantity').value = 1;
        document.getElementById('orderProductId').value = product.id;
        document.getElementById('orderTotalPrice').textContent = `Rp ${Number(harga).toLocaleString('id-ID')}`;
        document.getElementById('hiddenTotalPrice').value = harga;
        document.getElementById('orderForm').action = `/customer/order/direct/${product.id}`;
        console.log("Submit ke:", `/customer/order/direct/${product.id}`);


        document.getElementById('quantity').oninput = function () {
            const qty = parseInt(this.value) || 0;
            const total = harga * qty;
            document.getElementById('orderTotalPrice').textContent = `Rp ${Number(total).toLocaleString('id-ID')}`;
            document.getElementById('hiddenTotalPrice').value = total;
        };

        toggleAgreementFields(document.getElementById('payment_method').value);
        document.getElementById('orderModal').classList.remove('hidden');
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function toggleAgreementFields(value) {
        const akad = value === 'akad';
        document.getElementById('agreementSection').classList.toggle('hidden', !akad);
        document.getElementById('ktpSection').classList.toggle('hidden', !akad);
document.getElementById('agreementSection').style.display = akad ? 'block' : 'none';
document.getElementById('agreementUploadSection').style.display = akad ? 'block' : 'none';

        document.getElementById('agreementUploadSection').classList.toggle('hidden', !akad);
    }

    function confirmAgreement() {
        const method = document.querySelector('#orderModal #payment_method').value;
        if (method === 'akad') {
            const isChecked = document.getElementById('agreementCheck').checked;
            const ktp = document.getElementById('ktp_document');
            const akadDoc = document.getElementById('akad_file');

    console.log("method:", method);
    console.log("ktp exists?", ktp, ktp?.files.length);
    console.log("akad exists?", akadDoc, akadDoc?.files.length);

            if (!isChecked) {
                alert('Anda harus menyetujui akad terlebih dahulu.');
                return false;
            }
            if (!ktp.files.length) {
                alert('Upload KTP wajib.');
                return false;
            }
            if (!akadDoc.files.length) {
                alert('Dokumen akad wajib diunggah.');
                return false;
            }
        }
        return true;
    }

    function openDetailModal(id) {
        const product = productData.find(p => p.id === id);
        if (!product) return;

        document.getElementById('detailProductName').textContent = product.name;
        document.getElementById('detailVendor').textContent = 'Vendor: ' + product.vendor.name;
        document.getElementById('detailProductPrice').textContent = 'Rp ' + Number(product.price).toLocaleString('id-ID');
        document.getElementById('detailProductDescription').textContent = product.description ?? 'Tidak ada deskripsi tersedia.';

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection
