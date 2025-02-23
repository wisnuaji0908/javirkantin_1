@extends('seller.master')

@section('title', 'Edit Produk')

@section('content')
<div class="container my-4">
    <h1 class="text-center mb-4">Edit Produk</h1>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                </div>

                @if ($product->image)
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif

                <!-- Hidden input untuk simpan JSON toppings -->
                <input type="hidden" name="toppings_json" id="toppings_json">

                <div class="mb-3">
                    <label for="toppings" class="form-label fw-bold">Topping (Opsional)</label>
                    <div id="topping-container">
                        @php
                            $toppings = json_decode($product->toppings, true) ?? [];
                        @endphp
                        @foreach($toppings as $index => $topping)
                            <div class="d-flex mb-2 topping-item">
                                <input type="text" class="form-control me-2 topping-name" value="{{ $topping['name'] }}" placeholder="Nama topping">
                                <input type="number" class="form-control topping-price" value="{{ $topping['price'] }}" placeholder="Harga topping">
                                <button type="button" class="btn btn-danger btn-sm ms-2 removeTopping">X</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="addTopping">+ Tambah Topping</button>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let toppingIndex = {{ count($toppings) }};
    const toppingsJsonInput = document.getElementById('toppings_json');

    function updateToppingsJson() {
        const toppingItems = document.querySelectorAll('.topping-item');
        let toppingsArray = [];

        toppingItems.forEach((item) => {
            const name = item.querySelector('.topping-name').value;
            const price = item.querySelector('.topping-price').value;

            if (name.trim() !== '' && price !== '') {
                toppingsArray.push({ name, price });
            }
        });

        toppingsJsonInput.value = JSON.stringify(toppingsArray);
    }

    document.getElementById('addTopping').addEventListener('click', function () {
        const container = document.getElementById('topping-container');
        const newTopping = document.createElement('div');
        newTopping.classList.add('d-flex', 'mb-2', 'topping-item');
        newTopping.innerHTML = `
            <input type="text" class="form-control me-2 topping-name" placeholder="Nama topping">
            <input type="number" class="form-control topping-price" placeholder="Harga topping">
            <button type="button" class="btn btn-danger btn-sm ms-2 removeTopping">X</button>
        `;
        container.appendChild(newTopping);
        toppingIndex++;

        updateToppingsJson();
    });

    document.getElementById('topping-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('removeTopping')) {
            e.target.parentElement.remove();
            updateToppingsJson();
        }
    });

    document.querySelector('form').addEventListener('submit', function () {
        updateToppingsJson();
    });
</script>
@endsection
