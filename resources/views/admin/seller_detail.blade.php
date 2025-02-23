@extends('admin.master')

@section('title', 'Detail Seller')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 text-center animate-fade-in">📊 Dashboard Keuangan - {{ $seller->name  }}</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm p-3 mb-4 animate-fade-in">
                <h5 class="text-primary">💰 Total Pendapatan</h5>
                <p class="fs-4 fw-bold text-success">
                    Rp <span id="totalPendapatan">{{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm p-3 mb-4 animate-fade-in">
                <h5 class="text-primary">📦 Total Produk Terjual</h5>
                <p class="fs-4 fw-bold">
                    <span id="totalProdukTerjual">{{ number_format($totalProdukTerjual) }}</span> produk
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm p-3 mb-4 animate-fade-in">
                <h5 class="text-primary">✅ Total Transaksi Sukses</h5>
                <p class="fs-4 fw-bold text-success">
                    <span id="totalTransaksiSukses">{{ number_format($totalTransaksiSukses) }}</span> transaksi
                </p>
            </div>
        </div>
    </div>

    <h3 class="mb-3 animate-fade-in">📜 Daftar Transaksi Seller</h3>

    <div class="d-flex justify-content-between mb-4">
        <input type="text" id="searchOrder" class="form-control filter-input w-25" placeholder="🔎 Cari Order ID...">
        {{-- <select id="filterStatus" class="form-select w-25">
            <option value="all">Semua Transaksi</option>
            <option value="finish">Transaksi Berhasil</option>
        </select> --}}
    </div>

    <div class="card shadow-sm p-3 animate-fade-in">
        <table class="table table-hover table-bordered" id="orderTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $trx)
                <tr class="order-row" data-status="{{ $trx->order_status }}">
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-primary order-id">{{ $trx->order_id }}</td>
                    <td>{{ $trx->product->name ?? '-' }}</td>
                    <td>{{ $trx->quantity }}</td>
                    <td class="text-success">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($trx->order_status) }}</td>
                    <td>{{ $trx->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<script>
document.getElementById('searchOrder').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    document.querySelectorAll('.order-row').forEach(row => {
        let orderId = row.querySelector('.order-id').innerText.toLowerCase();
        row.style.display = orderId.includes(value) ? '' : 'none';
    });
});

document.getElementById('filterStatus').addEventListener('change', function () {
    let filter = this.value;
    document.querySelectorAll('.order-row').forEach(row => {
        let status = row.dataset.status;
        row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
    });
});
</script>
@endsection
