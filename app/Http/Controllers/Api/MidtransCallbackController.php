<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Product;

class MidtransCallbackController extends Controller
{
    public function handleMidtransCallback(Request $request)
    {
        Log::info('Midtrans Callback Data:', $request->all());

        // Validasi Signature Key
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // Ambil semua transaksi berdasarkan order_id
        $orders = Transaction::where('order_id', $request->order_id)->get();

        if ($orders->isEmpty()) {
            Log::error('Payment failed: Order tidak ditemukan.', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Transaction not found'], 200);
        }

        // Cek apakah semua transaksi sudah paid
        $allPaid = $orders->every(fn($order) => $order->status === 'paid');

        if ($allPaid) {
            Log::warning('Payment ignored: Semua order dalam transaksi ini sudah dibayar.', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Order sudah diproses sebelumnya.'], 200);
        }

        // Update semua transaksi dengan status settlement
        if ($request->transaction_status === 'settlement') {
            foreach ($orders as $order) {
                if ($order->status !== 'paid') {
                    $order->update([
                        'status' => 'paid',
                        'payment_method' => ucfirst(str_replace('_', ' ', $request->payment_type)),
                        'order_status' => 'processing'
                    ]);
                    Log::info('Order updated to paid & status processing:', ['order_id' => $order->order_id]);

                    // 🔍 Ambil produk yang dipesan dan update stoknya
                    $product = Product::find($order->product_id);
                    if ($product) {
                        $newStock = max(0, $product->stock - $order->quantity);
                        Log::info('Stock sebelum dikurangi:', ['product_id' => $product->id, 'stock' => $product->stock]);
                        $product->update(['stock' => $newStock]);
                        Log::info('Stock berhasil dikurangi:', ['product_id' => $product->id, 'new_stock' => $newStock]);
                    } else {
                        Log::error('Produk tidak ditemukan saat update stok.', ['product_id' => $order->product_id]);
                    }
                }
            }

            // ✅ Tambahin return response sukses dengan redirect URL ke halaman sukses
            return response()->json([
                'message' => 'Payment success',
                'redirect_url' => route('checkout.payment.success', ['order_id' => $request->order_id])
            ], 200);
        }

        // Kalau transaksi gagal
        elseif ($request->transaction_status === 'cancel' || $request->transaction_status === 'deny') {
            foreach ($orders as $order) {
                $order->update(['status' => 'failed']);
                Log::warning('Payment failed: Order marked as failed.', ['order_id' => $order->order_id]);
            }

            // ✅ Redirect ke halaman pembayaran gagal
            return redirect()->route('checkout.payment.failed')->with('error', 'Pembayaran gagal, silakan coba lagi.');
        }

        return response()->json(['message' => 'Notification processed'], 200);
    }
}
