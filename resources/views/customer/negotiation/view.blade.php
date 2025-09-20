@extends('layouts.app')

@section('content')
    <h2>Negosiasi Harga</h2>

    <p>Barang: {{ $negotiation->product->name }}</p>
    <p>Harga yang Diajukan: Rp{{ number_format($negotiation->requested_price, 2) }}</p>
    <p>Status: {{ ucfirst($negotiation->status) }}</p>

    @if($negotiation->status == 'pending' && Auth::id() == $negotiation->vendor_id)
        <form action="{{ route('negotiation.respond', $negotiation->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="offered_price">Harga yang Ditawarkan</label>
                <input type="number" class="form-control" name="offered_price" required>
            </div>
            <button type="submit" class="btn btn-success">Balas Negosiasi</button>
        </form>
    @endif
@endsection
