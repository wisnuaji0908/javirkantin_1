@extends('buyer.master')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container my-2">
    <h1 class="text-center mb-4">Keranjang Belanja</h1>

    <div class="mb-3 d-flex">
        <input type="text" id="searchProduct" class="form-control me-2" placeholder="Cari produk...">
        <button id="searchButton" class="btn btn-primary">Cari</button>
    </div>

    @if($carts->isEmpty())
        <p class="text-center">Keranjang kamu masih kosong.</p>
    @else
        <form id="cartForm">
            @foreach($carts as $sellerName => $items)
                <div class="card my-3 cart-group" data-seller="{{ $sellerName }}">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $sellerName }}</h5>
                        <input type="checkbox" class="select-shop" data-seller="{{ $sellerName }}">
                    </div>
                    <div class="card-body">
                        @foreach($items as $cart)
                            <div class="d-flex justify-content-between align-items-center mb-3 cart-item">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="select-product animate-checkbox"
                                        data-id="{{ $cart->id }}"
                                        data-product-id="{{ $cart->product->id }}"
                                        data-name="{{ $cart->product->name }}"
                                        data-price="{{ $cart->product->price }}"
                                        data-quantity="{{ $cart->quantity }}"
                                        data-toppings="@json($cart->product->toppings)"
                                        data-image="{{ asset('storage/' . ltrim($cart->product->image, '/')) }}">

<img src="{{ asset('storage/' . ltrim($cart->product->image, '/')) }}"
     width="60" height="60" class="rounded border mx-2 product-img">
                                    <div>
                                        <strong class="product-name">{{ $cart->product->name }}</strong>
                                        <p class="mb-0 text-muted">Rp {{ number_format($cart->product->price, 0, ',', '.') }} x {{ $cart->quantity }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('buyer.cart.destroy', $cart->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </form>

        <div class="d-flex justify-content-between align-items-center p-3 bg-light border-top">
            <h4>Total: <span id="totalPrice">Rp 0</span></h4>
            <button type="submit" class="btn btn-success" id="checkoutButton" disabled>Checkout</button>
        </div>
    @endif
</div>

<style>
    .cart-group {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .cart-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        transition: background 0.3s ease-in-out;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-item input[type="checkbox"] {
        transform: scale(1.2);
        margin-right: 10px;
    }
    .select-shop {
        transform: scale(1.2);
    }
    .animate-checkbox {
        transition: all 0.2s ease-in-out;
    }
    .animate-checkbox:checked {
        transform: scale(1.4);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let searchInput = document.getElementById("searchProduct");
    let searchButton = document.getElementById("searchButton");
    let checkboxes = document.querySelectorAll(".select-product");
    let selectShops = document.querySelectorAll(".select-shop");
    let totalPriceElem = document.getElementById("totalPrice");
    let checkoutButton = document.getElementById("checkoutButton");

    // Filter produk dengan tombol cari
    searchButton.addEventListener("click", function () {
        let filter = searchInput.value.toLowerCase();
        document.querySelectorAll(".cart-group").forEach(function (group) {
            let items = group.querySelectorAll(".cart-item");
            let hasVisibleItem = false;

            items.forEach(function (item) {
                let productName = item.getAttribute("data-product");
                if (productName.includes(filter)) {
                    item.style.display = "flex";
                    hasVisibleItem = true;
                } else {
                    item.style.display = "none";
                }
            });

            if (hasVisibleItem) {
                group.style.display = "block";
            } else {
                group.style.display = "none";
            }
        });
    });

    function updateTotalPrice() {
        let total = 0;
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                total += parseFloat(checkbox.getAttribute("data-price")) * parseInt(checkbox.getAttribute("data-quantity"));
            }
        });
        totalPriceElem.textContent = "Rp " + total.toLocaleString('id-ID');
        checkoutButton.disabled = total === 0;
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            let parentGroup = checkbox.closest(".cart-group");
            let allProducts = parentGroup.querySelectorAll(".select-product");
            let allChecked = Array.from(allProducts).every(prod => prod.checked);

            let shopCheckbox = parentGroup.querySelector(".select-shop");
            shopCheckbox.checked = allChecked;

            updateTotalPrice();
        });
    });

    selectShops.forEach(function (shopCheckbox) {
        shopCheckbox.addEventListener("change", function () {
            let parentGroup = shopCheckbox.closest(".cart-group");
            let allProducts = parentGroup.querySelectorAll(".select-product");

            allProducts.forEach(function (productCheckbox) {
                productCheckbox.checked = shopCheckbox.checked;
            });

            updateTotalPrice();
        });
    });

    checkoutButton.addEventListener("click", function () {
    let selectedProducts = [];
    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            let productId = checkbox.getAttribute("data-product-id"); // ✅ FIX: Pakai product_id!

            if (!productId) {
                alert("Terjadi kesalahan: product_id tidak ditemukan.");
                return;
            }

            let toppings = JSON.parse(checkbox.getAttribute("data-toppings") || "[]");

            selectedProducts.push({
                product_id: productId, // ✅ HARUS pakai product_id dari database
                name: checkbox.getAttribute("data-name"),
                price: parseFloat(checkbox.getAttribute("data-price")),
                quantity: parseInt(checkbox.getAttribute("data-quantity")),
                image: checkbox.getAttribute("data-image"),
                toppings: toppings.map(t => ({
                    id: t.id,
                    name: t.name,
                    price: t.price,
                    quantity: 0
                }))
            });
        }
    });

    if (selectedProducts.length > 0) {
        console.log("Produk yang dikirim ke checkout:", selectedProducts); // ✅ Debug checkout

        fetch("{{ route('checkout.direct') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ checkoutProducts: selectedProducts })
        })
        .then(response => response.json())
        .then(data => {
    console.log("Response dari server:", data); // ✅ Debug response

    if (data.success) {
        console.log("Redirect ke checkout harusnya terjadi...");
        setTimeout(() => {
            console.log("Redirecting ke:", data.redirect);
            window.location.replace(data.redirect);
        }, 500);
    } else {
        alert("Gagal: " + data.message);
    }
})
        .catch(error => {
            console.error("Error:", error);
            alert("Terjadi kesalahan. Silakan coba lagi.");
        });
    } else {
        alert("Pilih produk terlebih dahulu.");
    }
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
