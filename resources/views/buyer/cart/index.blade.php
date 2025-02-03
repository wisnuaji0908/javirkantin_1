@extends('buyer.master')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container my-4">
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
                            <div class="d-flex justify-content-between align-items-center mb-3 cart-item" data-product="{{ strtolower($cart->product->name) }}">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="select-product animate-checkbox" name="selected_products[]" value="{{ $cart->id }}" data-price="{{ $cart->product->price * $cart->quantity }}">
                                    <img src="{{ $cart->product->image ? asset('storage/' . $cart->product->image) : asset('images/default-profile.png') }}"
                                         width="60" height="60" class="rounded border mx-2">
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

    // Filter produk dengan tombol cari (TERBARU)
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

            // Sembunyikan grup seller kalau semua itemnya gak cocok
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
                total += parseFloat(checkbox.getAttribute("data-price"));
            }
        });
        totalPriceElem.textContent = "Rp " + total.toLocaleString('id-ID');
        checkoutButton.disabled = total === 0;
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", updateTotalPrice);
    });

    selectShops.forEach(function (shopCheckbox) {
        shopCheckbox.addEventListener("change", function () {
            let seller = shopCheckbox.getAttribute("data-seller");
            document.querySelectorAll(".cart-item input[type='checkbox']").forEach(function (checkbox) {
                if (checkbox.closest(".cart-group").querySelector(".card-header h5").textContent === seller) {
                    checkbox.checked = shopCheckbox.checked;
                }
            });
            updateTotalPrice();
        });
    });

    checkoutButton.addEventListener("click", function () {
        let selectedProducts = [];
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                selectedProducts.push(checkbox.value);
            }
        });

        if (selectedProducts.length > 0) {
            alert("Fitur pembelian akan segera hadir!");
        } else {
            alert("Pilih produk terlebih dahulu.");
        }
    });
});
</script>
@endsection
