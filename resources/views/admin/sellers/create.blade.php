@extends('admin.master')

@section('content')
<div class="container py-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Tambah Seller</h3>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.sellers.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input
                        type="text"
                        class="form-control form-control-lg border-secondary"
                        id="name"
                        name="name"
                        placeholder="Masukkan nama seller"
                        required>
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control form-control-lg border-secondary"
                        id="email"
                        name="email"
                        placeholder="Masukkan email seller"
                        required>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan style tambahan -->
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 15px;
    }
    .btn {
        border-radius: 10px;
    }
    .form-control {
        height: calc(2.5rem + 2px);
    }
</style>
@endsection
