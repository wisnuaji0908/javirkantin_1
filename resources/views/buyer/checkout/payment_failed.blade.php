@extends('buyer.master')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container my-5">
    <h1 class="text-center text-danger">Pembayaran Gagal!</h1>
    <p class="text-center">Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi atau hubungi admin.</p>

    <div class="text-center">
        <a href="{{ route('checkout.index') }}" class="btn btn-warning">Coba Lagi</a>
    </div>
</div>
@endsection
