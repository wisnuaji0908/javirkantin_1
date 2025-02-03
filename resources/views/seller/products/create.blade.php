@extends('seller.master')

@section('title', 'Tambah Produk')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Tambah Produk</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-bold">Harga</label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Masukkan harga" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label fw-bold">Stok</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" placeholder="Masukkan jumlah stok" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Gambar</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary ms-3">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tambahin feedback interaktif buat file upload
    document.getElementById('image').addEventListener('change', function (e) {
        const fileName = e.target.files[0]?.name || 'Pilih file';
        e.target.nextElementSibling.textContent = fileName;
    });
</script>
@endsection
