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
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .table-header {
        background-color: #007bff;
        color: #fff;
        text-align: center;
        font-size: 1.5rem;
        padding: 10px;
        border-radius: 8px 8px 0 0;
    }
    .status-active {
        color: green;
        font-weight: bold;
    }

    .status-blocked {
        color: red;
        font-weight: bold;
    }
</style>

<div class="container">
    <div class="table-header">Daftar Buyer</div>
    <div class="filter-container mt-3">
        <form method="GET" action="{{ route('admin.buyers.index') }}" class="d-flex">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control filter-input" placeholder="Filter Nama">
            <input type="text" name="email" value="{{ request('email') }}" class="form-control filter-input" placeholder="Filter Email">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('admin.buyers.index') }}" class="btn btn-secondary ms-2">Reset</a>
        </form>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($buyers as $buyer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $buyer->name }}</td>
                        <td>{{ $buyer->email }}</td>
                        <td>
                            <span class="{{ $buyer->is_blocked ? 'status-blocked' : 'status-active' }}">
                                {{ $buyer->is_blocked ? 'Diblokir' : 'Aktif' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.buyers.toggle-block', $buyer->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('POST')
                                <button class="btn btn-{{ $buyer->is_blocked ? 'success' : 'danger' }} btn-sm">
                                    {{ $buyer->is_blocked ? 'Unblokir' : 'Blokir' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada data buyer</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $buyers->withQueryString()->links('pagination::bootstrap-4') }}
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
