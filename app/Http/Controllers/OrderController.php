<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Buyer melihat daftar pesanan yang sudah dibayar
     */
    public function buyerOrders()
    {
        $orders = Transaction::where('buyer_id', Auth::id())
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function buyerOrderDetail($orderId, $sellerId)
    {
        $buyerId = Auth::id();

        // Cari order berdasarkan order_id DAN seller_id
        $order = Transaction::where('order_id', $orderId)
            ->where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId) // Tambahin pengecekan seller_id
            ->with(['product', 'seller', 'buyer'])
            ->first();

        if (!$order) {
            return redirect()->route('buyer.orders.index')->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
        }

        return view('buyer.orders.detail', compact('order'));
    }

    /**
     * Seller melihat dan mengelola pesanan yang sudah dibayar
     */
    public function sellerOrders()
    {
        $sellerId = Auth::user()->id;

        $orders = Transaction::where('status', 'paid')
            ->whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->with(['product', 'product.seller']) // FIX: Load seller juga
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $sellerId = Auth::user()->id;

        $order = Transaction::where('order_id', $orderId)
            ->where('seller_id', $sellerId)
            ->with(['product', 'buyer'])
            ->first();

        if (!$order) {
            return redirect()->route('seller.orders.index')->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
        }

        return view('seller.orders.detail', compact('order'));
    }
    /**
     * Seller mengubah status pesanan
     */
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:processing,ready,review,finish'
        ]);

        $sellerId = Auth::id(); // Seller yang sedang login

        // Ambil transaksi spesifik berdasarkan order_id dan seller_id
        $orders = Transaction::where('order_id', $orderId)
            ->where('seller_id', $sellerId)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['error' => 'Pesanan tidak ditemukan atau bukan milik Anda.'], 400);
        }

        foreach ($orders as $order) {
            // Update status sesuai permintaan seller
            if ($request->status === 'ready') {
                $order->update(['order_status' => 'ready']);

                // Redirect kalau status berubah jadi ready
                return redirect()->route('seller.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
            } elseif ($request->status === 'review') {
                $order->update(['order_status' => 'review']);

                // Ambil buyer dari transaksi
                $buyer = $order->buyer;

                // Ambil notifikasi sebelumnya
                $existingNotifications = json_decode($buyer->review_notification, true) ?? [];

                // Tambahkan notifikasi baru
                $existingNotifications[] = [
                    'order_id' => $order->order_id,
                    'product_name' => $order->product->name ?? 'Produk Tidak Diketahui',
                    'seller_name' => $order->product->seller->name ?? 'Seller Tidak Diketahui',
                    'seller_id' => $order->seller_id // 🔥 Tambahin seller_id ke array
                ];

                // Simpan notifikasi ke database buyer
                $buyer->update([
                    'review_notification' => json_encode($existingNotifications)
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui.']);
    }

    public function buyerConfirmOrder(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:finish',
            'seller_id' => 'required|exists:users,id' // Validasi seller_id harus ada di tabel users
        ]);

        $buyerId = Auth::id();
        $sellerId = $request->seller_id;

        // Cek apakah order yang diambil benar berdasarkan buyer & seller
        $orders = Transaction::where('order_id', $orderId)
            ->where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['error' => 'Pesanan tidak valid atau belum bisa dikonfirmasi.'], 400);
        }

        foreach ($orders as $order) {
            if ($order->order_status !== 'review') {
                return response()->json(['error' => 'Pesanan tidak valid atau belum bisa dikonfirmasi.'], 400);
            }
            $order->update(['order_status' => 'finish']);
        }

        // Update notifikasi buyer
        $buyer = $orders->first()->buyer;
        $existingNotifications = json_decode($buyer->review_notification, true) ?? [];

        // Filter notifikasi berdasarkan seller_id
        $updatedNotifications = array_filter($existingNotifications, function ($notification) use ($orderId, $sellerId) {
            return !($notification['order_id'] === $orderId && $notification['seller_id'] == $sellerId);
        });

        $buyer->update([
            'review_notification' => empty($updatedNotifications) ? null : json_encode(array_values($updatedNotifications))
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan telah selesai.',
            'new_status' => 'finish'
        ]);
    }
}
