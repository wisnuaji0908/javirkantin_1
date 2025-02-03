@extends('seller.master')

@section('title', 'Edit Produk')

@section('content')
<div class="container my-4">
    <h1 class="text-center mb-4">Edit Produk</h1>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="form-group mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stok -->
                <div class="form-group mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gambar -->
                <div class="form-group mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Preview Gambar Lama -->
                @if ($product->image)
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif

                <!-- Tombol -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
