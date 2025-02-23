@extends('buyer.master')

@section('title', 'Pesanan Saya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container my-2">
    <h1 class="text-center mb-4 fw-bold title-glow">📦 Pesanan Saya</h1>

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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="order-row">
                                    <td>
                                        <a href="{{ route('buyer.orders.show', ['orderId' => $order->order_id, 'sellerId' => $order->seller_id]) }}"
                                            class="text-primary fw-bold order-link">
                                            {{ $order->order_id }}
                                        </a>
                                    </td>
                                    <td class="fw-bold product-name">{{ $order->product->name }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    @php
        $reviewData = json_decode(Auth::user()->review_notification ?? '[]', true);
    @endphp

    let reviewDataArray = {!! json_encode($reviewData) !!};

    if (!Array.isArray(reviewDataArray) || reviewDataArray.length === 0) {
        console.warn("❌ Tidak ada data review yang valid!");
        return;
    }

    function showNextReview(index) {
        if (index >= reviewDataArray.length) return;

        let reviewData = reviewDataArray[index];
        let sellerId = reviewData.seller_id ?? null;

        if (!sellerId) {
            console.error("❌ Seller ID tidak ditemukan untuk order:", reviewData.order_id);
            return;
        }

        Swal.fire({
            title: "Konfirmasi Pesanan?",
            text: `Pesanan '${reviewData.product_name}' dari '${reviewData.seller_name}' sudah Anda terima?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Selesaikan!",
            cancelButtonText: "Belum"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/buyer/orders/update-status/${reviewData.order_id}`, { // ✅ Perbaiki path URL
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({
                        status: "finish",
                        seller_id: sellerId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire("Sukses!", "Pesanan telah selesai.", "success")
                        .then(() => {
                            showNextReview(index + 1);
                        });
                    } else {
                        Swal.fire("Error!", "Gagal memperbarui status pesanan.", "error");
                    }
                })
                .catch(error => {
                    console.error("❌ Fetch error:", error);
                    Swal.fire("Error!", "Terjadi kesalahan, coba lagi nanti.", "error");
                });
            }
        });
    }

    showNextReview(0);
});
        </script>
@endsection
