@extends('buyer.master')

@section('title', 'Detail Pesanan Saya')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4 fw-bold title-glow">📦 Detail Pesanan</h1>
    <div class="card shadow-lg p-4 animated-fade">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Order ID</th>
                    <td class="text-primary fw-bold">{{ $order->order_id }}</td>
                </tr>
                <tr>
                    <th>Nama Seller</th>
                    <td>{{ $order->seller->name ?? 'Tidak Diketahui' }}</td>
                </tr>
                <tr>
                    <th>Produk</th>
                    <td>{{ $order->product->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ $order->quantity }}</td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td class="text-success fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ $order->payment_method }}</td>
                </tr>
                <tr>
                    <th>Status Pesanan</th>
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
                <tr>
                    <th>Catatan</th>
                    <td>{{ $order->note ?? 'Tidak ada catatan' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pemesanan</th>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
            <a href="{{ route('buyer.orders.index') }}" class="btn btn-secondary mt-3">🔙 Kembali ke Pesanan</a>
        </div>
    </div>
</div>

<style>
.title-glow {
    text-shadow: 2px 2px 10px rgba(0, 174, 255, 0.8);
}
.animated-fade {
    animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
