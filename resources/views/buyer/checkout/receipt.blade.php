@php
    $totalPrice = 0;

    $products = $orders->map(function ($order) {
        return [
            'name' => $order->product->name,
            'price' => $order->product->price,
            'quantity' => $order->quantity,
            'toppings' => json_decode($order->toppings, true) ?? [],
        ];
    });
@endphp

@extends('buyer.master')

@section('title', 'Bukti Pembayaran')

@section('content')
<div class="container my-5">
    <h1 class="text-center text-success fade-in">Bukti Pembayaran</h1>

    <div class="card shadow p-4 fade-in">
        <div class="text-center mb-4">
            <h4 class="fw-bold">Kode Pesanan: <span class="text-primary">{{ $orders->first()->order_id }}</span></h4>
            <p class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($orders->first()->created_at)->format('d M Y, H:i') }}</p>
            <h5>Status:
                <span class="badge {{ $orders->first()->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                    {{ strtoupper($orders->first()->status) }}
                </span>
            </h5>
        </div>

        <table class="table table-bordered text-center fade-in">
            <thead class="table-success">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Topping</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @php
                        $subtotal = $order->product->price * $order->quantity;
                        $toppings = json_decode($order->toppings, true) ?? [];

                        if (!empty($toppings)) {
                            foreach ($toppings as $topping) {
                                $subtotal += ($topping['price'] * $topping['quantity']);
                            }
                        }

                        $totalPrice += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $order->product->name }}</td>
                        <td>Rp {{ number_format($order->product->price, 0, ',', '.') }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>
                            @if (!empty($toppings))
                                @foreach ($toppings as $topping)
                                    <div>{{ $topping['name'] }} (x{{ $topping['quantity'] }}) (+Rp {{ number_format($topping['price'], 0, ',', '.') }})</div>
                                @endforeach
                            @else
                                Tidak ada topping
                            @endif
                        </td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end">
            <h4>Total Pembayaran: <span class="fw-bold text-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span></h4>
        </div>
    </div>
</div>

<div class="text-center mt-4 fade-in">
    <button id="download-pdf" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Download Bukti</button>
    <a href="{{ route('buyer.shop.index') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>

<style>
    .fade-in {
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in.show {
        opacity: 1;
        transform: translateY(0);
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn {
            display: none;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.fade-in').forEach(el => {
            setTimeout(() => {
                el.classList.add('show');
            }, 300);
        });

        const downloadBtn = document.getElementById("download-pdf");

        if (downloadBtn) {
            downloadBtn.addEventListener("click", function () {
                try {
                    generatePDF();
                } catch (error) {
                    console.error("Error saat generate PDF:", error);
                    alert("Terjadi kesalahan saat membuat PDF!");
                }
            });
        } else {
            console.error("Tombol download tidak ditemukan!");
        }
    });

    function generatePDF() {
        if (!window.jspdf) {
            alert("Error: jsPDF belum dimuat!");
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        let yPos = 20;
        let pageHeight = doc.internal.pageSize.height;

        doc.setFont("helvetica", "bold");
        doc.setFontSize(20);
        doc.text("Bukti Pembayaran", 80, yPos);
        yPos += 20;

        doc.setFontSize(14);
        doc.text("Kode Pesanan: {{ $order->order_id }}", 20, yPos);
        yPos += 10;
        doc.text("Tanggal: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}", 20, yPos);
        yPos += 10;
        doc.text("Status: {{ strtoupper($order->status) }}", 20, yPos);
        yPos += 20;

        doc.text("Detail Pembelian:", 20, yPos);
        doc.setFont("helvetica", "normal");
        yPos += 10;

        let totalHarga = 0;

        // **AMBIL DATA PRODUK DARI BLADE KE JSON YANG AMAN**
        let products = @json($products);

        products.forEach(product => {
            if (yPos > pageHeight - 30) {
                doc.addPage();
                yPos = 20;
            }

            doc.text(`Produk: ${product.name}`, 20, yPos);
            yPos += 10;
            doc.text(`Harga: Rp ${product.price.toLocaleString("id-ID")}`, 20, yPos);
            yPos += 10;
            doc.text(`Jumlah: ${product.quantity}`, 20, yPos);
            yPos += 10;

            let subtotal = product.price * product.quantity;

            if (Array.isArray(product.toppings) && product.toppings.length > 0) {
                doc.text("Topping:", 20, yPos);
                yPos += 10;
                product.toppings.forEach(topping => {
                    let toppingHarga = topping.price * topping.quantity;
                    subtotal += toppingHarga;
                    doc.text(`- ${topping.name} (x${topping.quantity}) (+Rp ${topping.price.toLocaleString("id-ID")})`, 30, yPos);
                    yPos += 10;
                });
            } else {
                doc.text("Topping: -", 20, yPos);
                yPos += 10;
            }

            totalHarga += subtotal;
            yPos += 10;
        });

        if (yPos > pageHeight - 20) {
            doc.addPage();
            yPos = 20;
        }

        doc.setFont("helvetica", "bold");
        doc.text(`Total Pembayaran: Rp ${totalHarga.toLocaleString("id-ID")}`, 20, yPos + 10);

        doc.save(`Bukti_Pembayaran_{{ $order->order_id }}.pdf`);
    }

    // **FIX UNTUK PUSHMENU**
    try {
        if (typeof PushMenu !== "undefined" && typeof PushMenu.init === "function") {
            PushMenu.init();
        }
    } catch (error) {
        console.error("Error di PushMenu: ", error);
    }
</script>

@endsection
