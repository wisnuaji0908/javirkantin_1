@extends('buyer.master')

@section('title', 'Toko Seller')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container my-2">
    <h1 class="text-center mb-4">Toko Seller</h1>
    <!-- Filter Input -->
    <div class="d-flex justify-content-center mb-4">
        <input type="text" id="searchSeller" class="form-control filter-input w-50" placeholder="🔎 Cari Toko Seller...">
    </div>
    <div class="row">
        @foreach($sellers as $seller)
        <div class="col-md-4 mb-4 seller-card-container">
            <div class="card seller-card shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $seller->profile_image ? asset('storage/' . $seller->profile_image) : asset('images/default-profile.png') }}"
     alt="{{ $seller->name }}"
     class="seller-image rounded-circle mb-3">

                    <h5 class="seller-name">{{ $seller->name }}</h5>
                    <div class="button-group mt-3">
                        <a href="{{ route('buyer.shop.products', $seller->id) }}" class="btn btn-primary btn-sm">List Produk</a>
                        <a href="{{ route('buyer.chat.show', $seller->id) }}" class="btn btn-success">Chat Seller</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
/* Styling untuk halaman Toko Seller */
.seller-card {
    border-radius: 12px;
    background-color: #f9f9f9;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.seller-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.seller-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 2px solid #ddd;
}

.seller-name {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
}

.button-group .btn {
    margin: 5px;
    padding: 6px 12px;
    font-size: 0.9rem;
}

.filter-input {
    border-radius: 20px;
    padding: 10px;
    width: 300px;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border: 2px solid #007bff;
    box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}
.hidden {
    display: none !important;
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchSeller = document.getElementById("searchSeller");
        const sellers = document.querySelectorAll(".seller-card-container");

        searchSeller.addEventListener("keyup", function () {
            let value = this.value.toLowerCase().trim();
            sellers.forEach(seller => {
                let sellerName = seller.querySelector(".seller-name").innerText.toLowerCase();
                if (sellerName.includes(value)) {
                    seller.classList.remove("hidden"); // ✅ Tampilkan seller yang cocok
                } else {
                    seller.classList.add("hidden"); // ✅ Sembunyikan yang nggak cocok
                }
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
