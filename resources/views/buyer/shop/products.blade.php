@extends('buyer.master')

@section('title', 'Produk dari ' . $seller->name)

@section('content')
<!-- CSRF TOKEN -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Tambahin Noty.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

<div class="container my-4">
    <h1 class="text-center mb-4">Produk dari {{ $seller->name }}</h1>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card product-card shadow-sm">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-profile.png') }}"
                     class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="product-name">{{ $product->name }}</h5>
                    <p class="product-price">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="product-stock">Stok: {{ $product->stock }}</p>

                    <form class="add-to-cart-form" method="POST" action="{{ route('buyer.cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="input-group mb-2">
                            <input type="number" name="quantity" class="form-control quantity-input" value="1" min="1" max="{{ $product->stock }}" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success btn-sm add-to-cart-button">
                                    <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tombol Beli Sekarang -->
                    <button class="btn btn-primary btn-sm w-100 buy-button"
                        data-product-id="{{ $product->id }}"
                        data-price="{{ $product->price }}">
                        Beli Sekarang
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
/* Styling untuk halaman Produk Seller */
.product-card {
    border-radius: 12px;
    background-color: #f9f9f9;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.card-img-top {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.product-name {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
    text-align: center;
}

.product-price {
    font-size: 1rem;
    color: #28a745;
    font-weight: bold;
    margin: 10px 0;
}

.product-stock {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 15px;
}

.buy-button {
    background-color: #007bff;
    border-color: #007bff;
    font-size: 0.9rem;
    font-weight: bold;
}

.buy-button:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.add-to-cart-button {
    font-size: 0.9rem;
    font-weight: bold;
}

.success-message {
    display: none;
    background-color: #28a745;
    color: white;
    padding: 10px;
    margin-top: 10px;
    border-radius: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("Produk Seller page loaded!");

    // // Tombol beli sekarang
    // document.querySelectorAll('.buy-button').forEach(function (button) {
    //     button.addEventListener('click', function () {
    //         alert('Fitur pembelian akan segera hadir!');
    //     });
    // });

    // Beli Sekarang - AJAX
    document.querySelectorAll('.buy-button').forEach(button => {
    button.addEventListener('click', function () {
        let productId = this.closest('.card-body').querySelector('input[name="product_id"]').value;
        let quantity = this.closest('.card-body').querySelector('.quantity-input').value;

        let checkoutData = [
            {
                product_id: productId,
                quantity: quantity,
                toppings: [] // Kosongkan topping kalau tidak ada
            }
        ];

        console.log("Checkout Data yang dikirim:", checkoutData); // ✅ Tambahin ini buat debug

        fetch("{{ route('checkout.direct') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                checkoutProducts: checkoutData
            })
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
            alert("Terjadi kesalahan, coba lagi.");
        });
    });
});

    // Tambah ke keranjang pakai AJAX
    document.querySelectorAll('.add-to-cart-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let submitButton = this.querySelector('.add-to-cart-button');

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambah...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => response.json()).then(data => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-cart-plus"></i> Tambah ke Keranjang';

                if (data.success) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: 'Produk berhasil ditambahkan ke keranjang!',
                        timeout: 3000
                    }).show();
                } else {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: 'Gagal menambahkan produk ke keranjang.',
                        timeout: 3000
                    }).show();
                }
            }).catch(error => {
                console.error("Error:", error);
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-cart-plus"></i> Tambah ke Keranjang';
                new Noty({
                    type: 'error',
                    layout: 'topRight',
                    text: 'Terjadi kesalahan. Coba lagi.',
                    timeout: 3000
                }).show();
            });
        });
    });
});
</script>
@endsection
