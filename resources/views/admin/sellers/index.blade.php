@extends('admin.master')

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
    <h1 class="my-4 text-center">Daftar Seller</h1>
    <div class="filter-container">
        <form method="GET" action="{{ route('admin.sellers.index') }}" class="d-flex">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control filter-input" placeholder="Filter Nama">
            <input type="text" name="email" value="{{ request('email') }}" class="form-control filter-input" placeholder="Filter Email">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary ms-2">Reset</a>
        </form>
        <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary">Tambah Seller</a>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sellers as $seller)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $seller->name }}</td>
                        <td>{{ $seller->email }}</td>
                        <td>
                            {{-- <a href="{{ route('admin.sellers.edit', $seller->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                            <form action="{{ route('admin.sellers.delete', $seller->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada data seller</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sellers->withQueryString()->links('pagination::bootstrap-4') }}
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
