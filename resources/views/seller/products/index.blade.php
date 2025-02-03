@extends('seller.master')

@section('title', 'List Produk')

@section('page-title', 'List Produk')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty/lib/noty.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty/lib/themes/mint.css">
<script src="https://cdn.jsdelivr.net/npm/noty/lib/noty.min.js"></script>
<style>
    .filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-input {
        width: 200px;
        margin-right: 10px;
    }

    .table-container {
        overflow-x: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
        text-align: center;
        vertical-align: middle;
    }

    .btn-primary {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }

    .btn-primary:hover {
        background-color: #45a049;
        border-color: #45a049;
    }

    .btn-warning {
        background-color: #FFA726;
        border-color: #FFA726;
    }

    .btn-warning:hover {
        background-color: #FF9800;
        border-color: #FF9800;
    }

    .btn-danger {
        background-color: #EF5350;
        border-color: #EF5350;
    }

    .btn-danger:hover {
        background-color: #E53935;
        border-color: #E53935;
    }
</style>

<div class="container">
    <h1 class="my-4 text-center">Produk Saya</h1>
    <div class="filter-container">
        <form method="GET" action="{{ route('seller.products.index') }}" class="d-flex">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control filter-input" placeholder="Filter Nama">
            <input type="text" name="price" value="{{ request('price') }}" class="form-control filter-input" placeholder="Filter Harga">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('seller.products.index') }}" class="btn btn-secondary ms-2">Reset</a>
        </form>
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td><img src="{{ asset('storage/' . $product->image) }}" alt="Foto Produk" style="width: 80px;"></td>
                        <td>
                            <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data produk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            new Noty({
                text: "{{ session('success') }}",
                type: 'success',
                layout: 'topRight',
                timeout: 3000
            }).show();
        @endif
        @if(session('error'))
            new Noty({
                text: "{{ session('error') }}",
                type: 'error',
                layout: 'topRight',
                timeout: 3000
            }).show();
        @endif
    });
</script>
@endsection
