@extends('seller.master')

@section('title', 'Pesanan Masuk')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container my-5">
    <h1 class="text-center mb-4 fw-bold title-glow">📦 Pesanan Masuk</h1>

    <!-- Filter Input -->
    <div class="d-flex justify-content-between mb-4">
        <input type="text" id="searchOrder" class="form-control filter-input w-25" placeholder="🔎 Cari Order ID...">
        <input type="text" id="searchProduct" class="form-control filter-input w-25" placeholder="🔎 Cari Nama Produk...">
    </div>

    <div class="card shadow-lg p-4 animated-fade">
        <div class="card-body">
            @if ($orders->isEmpty())
                <p class="text-muted text-center">🚫 Belum ada pesanan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover text-center animated-table" id="orderTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="order-row">
                                    <td>
                                        <a href="{{ route('seller.orders.show', $order->order_id) }}" class="text-primary fw-bold order-link">
                                            {{ $order->order_id }}
                                        </a>
                                    </td>
                                    <td class="fw-bold product-name">{{ optional($order->product)->name ?? 'Produk Tidak Diketahui' }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="text-success fw-bold price-text">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->order_status === 'pending')
                                            <span class="badge bg-warning text-dark">⏳ Sedang Diproses</span>
                                        @elseif($order->order_status === 'processing')
                                            <span class="badge bg-primary">👨‍🍳 Sedang Disiapkan</span>
                                        @elseif($order->order_status === 'ready')
                                            <span class="badge bg-success">✅ Siap Diambil</span>
                                        @elseif($order->order_status === 'review')
                                            <span class="badge bg-info">📌 Sedang Ditinjau</span>
                                        @elseif($order->order_status === 'finish')
                                            <span class="badge bg-info">✅ Sudah Diambil</span>
                                        @else
                                            <span class="badge bg-secondary">❓ Tidak Diketahui</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->order_status === 'processing')
                                            <form action="{{ route('seller.orders.update-status', $order->order_id) }}" method="POST" class="status-form">
                                                @csrf
                                                <input type="hidden" name="status" value="ready">
                                                <button type="submit" class="btn btn-success btn-sm btn-animate">Tandai Sudah Siap</button>
                                            </form>
                                        @elseif($order->order_status === 'ready')
                                        <button class="btn btn-warning btn-sm btn-animate mark-received"
                                            data-order-id="{{ $order->order_id }}"
                                            data-seller-id="{{ $order->seller_id }}"
                                            data-product-name="{{ optional($order->product)->name ?? 'Produk Tidak Diketahui' }}"
                                            data-seller-name="{{ optional($order->product->seller)->name ?? 'Seller Tidak Diketahui' }}">
                                            Sudah Diambil?
                                        </button>
                                        @elseif($order->order_status === 'review')
                                            <button class="btn btn-secondary btn-sm" disabled>📌 Ditinjau Buyer</button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>✔️ Sudah Selesai</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4 pagination-custom">
                    {{ $orders->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Noty.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

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

/* Animasi Tombol */
.btn-animate {
    transition: all 0.3s ease-in-out;
}
.btn-animate:hover {
    transform: scale(1.05);
    box-shadow: 0px 4px 10px rgba(0, 255, 132, 0.5);
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
.order-link {
    text-decoration: none;
    transition: color 0.3s ease;
}
.order-link:hover {
    color: #ff5733;
    text-decoration: underline;
}

/* Harga Text Glow */
.price-text {
    animation: glow 1.5s infinite alternate;
}
@keyframes glow {
    from { text-shadow: 0 0 5px #4CAF50; }
    to { text-shadow: 0 0 15px #4CAF50; }
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.pagination li {
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination li a,
.pagination li span {
    padding: 6px 10px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 5px;
    text-decoration: none;
    color: #007bff;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease-in-out;
}

.pagination li a:hover {
    background: #007bff;
    color: white;
}

.pagination .active span {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination .disabled span {
    color: #ccc;
    border-color: #ccc;
}

.page-arrow {
    font-size: 18px;
}

</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.body.addEventListener("click", function (event) {
    if (!event.target.matches(".mark-received")) return;

    let button = event.target; // Ambil tombol yang diklik
    let orderId = button.dataset.orderId; // Ambil data dari `data-order-id`
    let sellerId = button.dataset.sellerId; // Ambil seller ID
    let productName = button.dataset.productName || "Produk Tidak Diketahui";
    let sellerName = button.dataset.sellerName || "Seller Tidak Diketahui";

    // Debugging di Console
    console.log("ORDER ID:", orderId);
    console.log("PRODUCT NAME:", productName);
    console.log("SELLER NAME:", sellerName);

    // Cek apakah ada data yang null
    if (!orderId) {
        console.error("❌ Order ID tidak ditemukan! Pastikan atribut tersedia.");
        return;
    }

    Swal.fire({
        title: "Konfirmasi Pengambilan?",
        text: `Apakah Anda yakin buyer telah mengambil pesanan ${productName} dari ${sellerName}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Buyer Sudah Ambil"
    }).then((result) => {
        if (result.isConfirmed) {
            // Hapus tombol lebih lambat untuk memastikan atributnya tetap bisa diakses
            let row = button.closest("tr");
            let statusCell = row ? row.querySelector("td:nth-child(5)") : null;

            fetch(`/seller/orders/update-status/${orderId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ status: "review", seller_id: sellerId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: data.message,
                        timeout: 2000
                    }).show();

                    // Update tampilan status langsung
                    if (statusCell) {
                        statusCell.innerHTML = `<span class="badge bg-info">📌 Sedang Ditinjau</span>`;
                    }

                    // Delay sebelum tombol dihapus biar nggak null
                    setTimeout(() => {
                        if (button && button.parentNode) {
                            button.remove();
                        }
                    }, 500); // Hapus tombol setelah 500ms (setengah detik)
                } else {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: "Gagal memperbarui status pesanan!",
                        timeout: 2000
                    }).show();
                }
            })
            .catch(error => console.error("❌ Fetch error:", error));
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Cek apakah ada notifikasi dari session
    @if(session('success'))
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: '{{ session('success') }} 🎉',
            timeout: 2000
        }).show();
    @endif

    // Filter Order ID
    document.getElementById('searchOrder').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        document.querySelectorAll('#orderTable tbody tr').forEach(row => {
            let orderId = row.cells[0].innerText.toLowerCase();
            row.style.display = orderId.includes(value) ? '' : 'none';
            if (orderId.includes(value)) row.classList.add("highlight");
            else row.classList.remove("highlight");
        });
    });

    // Filter Nama Produk
    document.getElementById('searchProduct').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        document.querySelectorAll('#orderTable tbody tr').forEach(row => {
            let productName = row.cells[1].innerText.toLowerCase();
            row.style.display = productName.includes(value) ? '' : 'none';
            if (productName.includes(value)) row.classList.add("highlight");
            else row.classList.remove("highlight");
        });
    });

    // Animasi highlight
    document.querySelectorAll(".order-row").forEach(row => {
        row.addEventListener("mouseenter", function () {
            row.classList.add("animated-fade");
        });
        row.addEventListener("mouseleave", function () {
            row.classList.remove("animated-fade");
        });
    });
});
</script>

@endsection
