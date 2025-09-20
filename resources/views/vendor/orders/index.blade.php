@extends('layouts.vendor')

@section('title', '📥 Pesanan Masuk')

@section('content')
    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <p class="text-gray-600">Belum ada pesanan masuk.</p>
    @else
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto border text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Order ID</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Status Negosiasi</th>
                        <th class="px-4 py-2">Alamat</th>
                        <th class="px-4 py-2">Metode Pembayaran</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $trackingStatus = $order->tracking->status ?? 'diproses';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->product->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->customer->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->quantity }}</td>

                            <td class="px-4 py-2">
                                @if($order->negotiation && $order->negotiation->status === 'disetujui')
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">🤝 Negosiasi</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">🛒 Reguler</span>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                @if($order->negotiation)
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                        @if($order->negotiation->status == 'menunggu') bg-yellow-500 text-white
                                        @elseif($order->negotiation->status == 'disetujui') bg-green-500 text-white
                                        @elseif($order->negotiation->status == 'ditolak') bg-red-500 text-white
                                        @endif">
                                        {{ ucfirst($order->negotiation->status) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Tanpa negosiasi</span>
                                @endif
                            </td>

                            <td class="px-4 py-2">{{ $order->shipping_address ?? '-' }}</td>

                            <td class="px-4 py-2">
                                @if ($order->payment_method === 'akad')
                                    Bayar Saat Akad
                                @elseif ($order->payment_method === 'cod')
                                    Bayar Di Tempat
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-2 capitalize">
                                {{ $order->status }}
                            </td>

                            <td class="px-4 py-2">
                                @if ($order->status === 'menunggu konfirmasi')
                                    @if ($order->payment_method === 'akad')
                                        <a href="{{ route('vendor.orders.viewAkadDokumen', $order->id) }}" class="text-blue-500 hover:underline">📄 Lihat Dokumen</a>

                                        @if($order->dokumenSudahValid)
                                            <form action="{{ route('vendor.orders.accept', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button class="text-green-600 hover:underline ml-2">✔ Terima & Proses</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 ml-2">✔ Menunggu Validasi</span>
                                        @endif
                                    @elseif ($order->payment_method === 'cod')
                                        <form action="{{ route('vendor.orders.accept', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="text-green-600 hover:underline">✔ Terima & Proses</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">Metode tidak dikenali</span>
                                    @endif
                                @elseif ($trackingStatus === 'diproses')
                                    <button onclick="openModal({{ $order->id }})" class="text-blue-600 hover:underline text-sm">
                                        📦 Kirim
                                    </button>
                                @elseif ($trackingStatus === 'dikirim')
                                    <a href="{{ route('vendor.monitoring') }}" class="text-green-700 hover:underline text-sm">
                                        🚚 Monitoring
                                    </a>
                                @elseif ($trackingStatus === 'selesai')
                                    <span class="text-gray-500 text-sm">✔ Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Modal Pengiriman --}}
    <div id="shippingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md overflow-y-auto max-h-screen">
            <h2 class="text-xl font-bold mb-4">📦 Atur Pengiriman</h2>
            <form id="shippingForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="estimated_date" class="block text-sm font-medium text-gray-700">Tanggal Estimasi</label>
                    <input type="date" name="estimated_date" id="estimated_date"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-indigo-200" required>
                </div>

                <div class="mb-4">
                    <label for="estimated_time" class="block text-sm font-medium text-gray-700">Jam Estimasi</label>
                    <input type="time" name="estimated_time" id="estimated_time"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-indigo-200" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openModal(orderId) {
        const form = document.getElementById('shippingForm');
        form.action = `/vendor/orders/${orderId}/shipping`;
        document.getElementById('shippingModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('shippingModal').classList.add('hidden');
    }
</script>
@endsection
