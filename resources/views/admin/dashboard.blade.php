@section('title', 'Dashboard Pendapatan Seller')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4 fw-bold title-glow">📊 Dashboard Pendapatan Seller</h1>

    <!-- Filter Input -->
    <div class="d-flex justify-content-center mb-4">
        <input type="text" id="searchSeller" class="form-control filter-input w-50" placeholder="🔎 Cari Nama Seller...">
    </div>

    <div class="card shadow-lg p-4 animated-fade">
        <div class="card-body">
            @if ($sellerEarnings->isEmpty())
                <p class="text-muted text-center">🚫 Belum ada transaksi yang selesai.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover text-center animated-table" id="sellerTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Seller</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sellerEarnings as $seller)
                                <tr class="seller-row">
                                    <td class="fw-bold seller-name">
                                        <a href="{{ route('admin.seller.detail', ['sellerId' => $seller['seller_id']]) }}" class="seller-link">
                                            {{ $seller['seller_name'] }}
                                        </a>
                                    </td>
                                    <td class="text-success fw-bold">Rp {{ number_format($seller['total_earnings'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Efek Glow untuk Judul */
.title-glow {
    text-shadow: 2px 2px 10px rgba(0, 174, 255, 0.8);
}

/* Animasi Fade In */
.animated-fade {
    animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hover Effect pada Tabel */
.animated-table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transition: background-color 0.3s ease;
}

/* Filter Input */
.filter-input {
    border-radius: 20px;
    padding: 10px;
    transition: all 0.3s ease;
}
.filter-input:focus {
    border: 2px solid #007bff;
    box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
}

/* Order Link Animation */
.seller-name {
    text-decoration: none;
    transition: color 0.3s ease;
}
.seller-name:hover {
    color: #ff5733;
    text-decoration: underline;
}

.hidden {
    display: none !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchSeller = document.getElementById("searchSeller");
    const sellerRows = document.querySelectorAll(".seller-row");

    searchSeller.addEventListener("keyup", function () {
        let value = this.value.toLowerCase().trim();
        sellerRows.forEach(row => {
            let sellerName = row.querySelector(".seller-name").innerText.toLowerCase();
            if (sellerName.includes(value)) {
                row.classList.remove("hidden"); // ✅ Tampilkan seller yang cocok
            } else {
                row.classList.add("hidden"); // ✅ Sembunyikan yang nggak cocok
            }
        });
    });
});
</script>
@endsection
