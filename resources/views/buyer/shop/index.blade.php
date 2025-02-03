@extends('buyer.master')

@section('title', 'Toko Seller')

@section('content')
<div class="container my-4">
    <h1 class="text-center mb-4">Toko Seller</h1>
    <div class="row">
        @foreach($sellers as $seller)
        <div class="col-md-4 mb-4">
            <div class="card seller-card shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $seller->profile_image ? asset('storage/' . $seller->profile_image) : asset('images/default-profile.png') }}"
     alt="{{ $seller->name }}"
     class="seller-image rounded-circle mb-3">

                    <h5 class="seller-name">{{ $seller->name }}</h5>
                    <div class="button-group mt-3">
                        <a href="{{ route('buyer.shop.products', $seller->id) }}" class="btn btn-primary btn-sm">List Produk</a>
                        <a href="{{ route('buyer.chat.show', $seller->id) }}" class="btn btn-success">Chat Seller</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
/* Styling untuk halaman Toko Seller */
.seller-card {
    border-radius: 12px;
    background-color: #f9f9f9;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.seller-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.seller-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 2px solid #ddd;
}

.seller-name {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
}

.button-group .btn {
    margin: 5px;
    padding: 6px 12px;
    font-size: 0.9rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("Toko Seller page loaded!");
});
</script>
@endsection
