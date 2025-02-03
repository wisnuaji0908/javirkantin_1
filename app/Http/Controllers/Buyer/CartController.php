<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $buyerId = Auth::id();

        // Pastikan Cart include relasi product -> seller
        $carts = Cart::where('buyer_id', $buyerId)
            ->with(['product.seller'])
            ->get()
            ->groupBy('product.seller.name'); // Group by nama toko seller

        return view('buyer.cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        \Log::info('Data yang diterima:', $request->all());

        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $buyerId = Auth::id();

            // Cek apakah produk sudah ada di keranjang
            $cartItem = Cart::where('buyer_id', $buyerId)
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $request->quantity);
            } else {
                Cart::create([
                    'buyer_id' => $buyerId,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error menambahkan ke keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);

            if ($cart->buyer_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa menghapus item ini!'
                ], 403);
            }

            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
