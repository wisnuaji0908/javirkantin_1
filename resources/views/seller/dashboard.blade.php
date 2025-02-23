@section('content')
<div class="container-fluid">
    <h1 class="mb-4 text-center animate-fade-in">📊 Dashboard Keuangan</h1>

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

    <!-- Filter Input -->
    <div class="d-flex justify-content-between mb-4">
        <input type="text" id="searchOrder" class="form-control filter-input w-25" placeholder="🔎 Cari Order ID...">
    </div>

    <h3 class="mb-3 animate-fade-in">📜 Daftar Transaksi Sukses</h3>
    <div class="card shadow-sm p-3 animate-fade-in">
        <table class="table table-hover table-bordered" id="orderTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksiSukses as $index => $trx)
                <tr class="animate-fade-in">
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-primary order-id">{{ $trx->order_id }}</td>
                    <td>{{ $trx->product->name ?? '-' }}</td>
                    <td>{{ $trx->quantity }}</td>
                    <td class="text-success">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                    <td>{{ $trx->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container">
            {{ $transaksiSukses->links() }}
        </div>
    </div>
</div>

<!-- Tambahin Script Animasi -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    function animateValue(id, start, end, duration) {
        let obj = document.getElementById(id);
        let range = end - start;
        let startTime = new Date().getTime();
        let endTime = startTime + duration;
        let step = function() {
            let now = new Date().getTime();
            let remaining = Math.max((endTime - now) / duration, 0);
            let value = Math.round(end - (remaining * range));
            obj.innerText = value.toLocaleString('id-ID');
            if (remaining > 0) {
                requestAnimationFrame(step);
            }
        };
        step();
    }

    animateValue("totalPendapatan", 0, {{ $totalPendapatan }}, 2000);
    animateValue("totalProdukTerjual", 0, {{ $totalProdukTerjual }}, 1500);
    animateValue("totalTransaksiSukses", 0, {{ $totalTransaksiSukses }}, 1800);
});
</script>
<!-- Script untuk Filter -->
<script>
    document.getElementById('searchOrder').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#orderTable tbody tr').forEach(row => {
        let orderIdCell = row.querySelector('.order-id');
        if (orderIdCell) {
            let orderId = orderIdCell.innerText.toLowerCase();
            let match = orderId.includes(value);
            row.style.display = match ? '' : 'none';
            row.classList.toggle("highlight", match);
        }
    });
});
    </script>

<!-- Tambahin CSS -->
<style>
    .dashboard-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .animate-fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease-in-out forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .filter-input {
    border-radius: 20px;
    padding: 10px;
    transition: all 0.3s ease;
}
.filter-input:focus {
    border: 2px solid #007bff;
    box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
}
.order-row.highlight {
    background-color: rgba(0, 255, 132, 0.2);
    transition: background-color 0.3s ease-in-out;
}
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>
@endsection
