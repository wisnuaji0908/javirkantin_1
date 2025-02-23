@extends('buyer.master')

@section('title', 'Checkout')

@section('content')
<div class="container my-2">
    <h1 class="text-center mb-4 fw-bold">Ringkasan Pesanan</h1>
    <div class="card shadow-sm p-4">
        <div class="card-body">
            <h5 class="fw-bold">Detail Produk</h5>
            <div class="border rounded p-3 bg-white">
                {{-- <pre>{{ print_r($checkoutProducts, true) }}</pre> --}}
                @foreach ($checkoutProducts as $index => $product)
                <div class="mb-3 p-2 border-bottom">
                    <h6 class="mb-1">{{ $product['name'] }}</h6>
                    <p class="text-muted mb-1">Harga: <strong>Rp {{ number_format($product['price'], 0, ',', '.') }}</strong></p>

                    <input type="hidden" class="product-id" value="{{ $product['id'] }}">

                    <p class="text-muted mb-1">Jumlah:
                        <input type="number" class="form-control d-inline w-25 quantity-input"
                               value="{{ $product['quantity'] }}" min="1"
                               data-index="{{ $index }}" data-price="{{ $product['price'] }}">
                    </p>

                    <p class="text-muted">Topping:</p>
                    @if (!empty($product['toppings']) && is_array($product['toppings']) && count($product['toppings']) > 0)
                        @foreach($product['toppings'] as $toppingIndex => $topping)
                        <div class="row mb-2">
                            <div class="col-6">
                                <label>{{ $topping['name'] }} (+Rp {{ number_format($topping['price'], 0, ',', '.') }})</label>
                            </div>
                            <div class="col-3">
                                <input type="hidden" class="topping-id" value="{{ $topping['id'] ?? 'TOPPING-' . $loop->index }}">
                                <input type="number" class="form-control topping-quantity"
                                       name="checkoutProducts[{{ $index }}][toppings][{{ $toppingIndex }}][quantity]"
                                       min="0"
                                       value="{{ $topping['quantity'] ?? 0 }}"
                                       data-product-index="{{ $index }}"
                                       data-topping-index="{{ $toppingIndex }}"
                                       data-price="{{ $topping['price'] }}">
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada topping tersedia</p>
                    @endif
                    <!-- Input Catatan -->
                    <div class="mt-2">
                        <label for="note-{{ $index }}" class="form-label">Catatan:</label>
                        <textarea class="form-control product-note" id="note-{{ $index }}" rows="2" placeholder="Masukkan catatan untuk seller..."></textarea>
                    </div>
                </div>
                @endforeach

                <h5 class="fw-bold mt-3">Total: <span id="total-price">Rp 0</span></h5>
            </div>

            <h5 class="fw-bold mt-4">Metode Pembayaran</h5>
            <div class="border rounded p-3 bg-white">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="online" checked>
                    <label class="form-check-label">Online</label>
                </div>
            </div>

            <button id="pay-button" class="btn btn-success w-100 mt-3 fw-bold">Bayar Sekarang</button>
        </div>
    </div>
</div>

<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    function updateTotalPrice() {
        let total = 0;

        document.querySelectorAll('.quantity-input').forEach(input => {
            let price = parseFloat(input.getAttribute("data-price"));
            let quantity = parseInt(input.value);
            let productTotal = price * quantity;

            let toppingTotal = 0;
            document.querySelectorAll(`.topping-quantity[data-product-index="${input.dataset.index}"]`).forEach(toppingInput => {
                let toppingPrice = parseFloat(toppingInput.getAttribute("data-price"));
                let toppingQty = parseInt(toppingInput.value);
                toppingTotal += toppingPrice * toppingQty;
            });

            total += productTotal + toppingTotal;
        });

        document.getElementById("total-price").textContent = "Rp " + total.toLocaleString('id-ID');
    }

    updateTotalPrice();

    document.querySelectorAll('.quantity-input, .topping-quantity').forEach(input => {
        input.addEventListener("input", updateTotalPrice);
    });

    document.getElementById('pay-button').addEventListener('click', function () {
        let checkoutData = [];

        document.querySelectorAll('.quantity-input').forEach(input => {
            let productId = input.closest('.border-bottom').querySelector('.product-id').value;
            let quantity = parseInt(input.value); // ✅ Ambil nilai terbaru dari input quantity
            let toppings = [];

            // ✅ Ambil catatan yang benar untuk produk ini
            let noteInput = input.closest('.border-bottom').querySelector('.product-note');
            let note = noteInput ? noteInput.value.trim() : ''; // Jika note ada, ambil nilainya

            document.querySelectorAll(`.topping-quantity[data-product-index="${input.dataset.index}"]`).forEach(toppingInput => {
                let toppingId = toppingInput.closest('.row').querySelector('.topping-id').value;
                let toppingQty = parseInt(toppingInput.value);
                let toppingPrice = parseFloat(toppingInput.getAttribute('data-price'));

                if (toppingQty > 0) {
                    toppings.push({
                        id: toppingId,
                        name: toppingInput.closest('.row').querySelector('label').innerText.split('(')[0].trim(),
                        price: toppingPrice,
                        quantity: toppingQty
                    });
                }
            });

            checkoutData.push({
                product_id: productId, // ✅ Pastikan pakai "product_id" bukan "id"
                quantity: quantity,
                toppings: toppings,
                note: note // ✅ Simpan catatan
            });
        });

        console.log("Checkout Data (Before Sending):", checkoutData);

        fetch("{{ route('checkout.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ checkoutProducts: checkoutData })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response dari server:", data);
            if (data.success) {
                console.log("Snap Token:", data.snap_token);
                snap.pay(data.snap_token);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection
