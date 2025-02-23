<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Load konfigurasi dari config/midtrans.php
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    public function index()
    {
        $checkoutProducts = session()->get('checkoutProducts');

        Log::info('Checkout Session Sebelum View:', ['checkoutProducts' => $checkoutProducts]);

        if (!$checkoutProducts || empty($checkoutProducts)) {
            return redirect()->route('buyer.shop.index')->with('error', 'Tidak ada produk yang dipilih.');
        }

        // Ambil produk dari database
        $products = Product::whereIn('id', array_column($checkoutProducts, 'id'))->get();

        // Pastikan topping selalu dalam format array
        foreach ($products as $product) {
            $product->toppings = json_decode($product->toppings, true) ?? [];
        }

        return view('buyer.checkout.index', compact('checkoutProducts', 'products'));
    }


    public function process(Request $request)
    {
        try {
            Log::info('Data dari request checkout:', ['request' => $request->all()]);

            $checkoutProducts = $request->input('checkoutProducts');

            if (!$checkoutProducts || empty($checkoutProducts)) {
                return response()->json(['success' => false, 'message' => 'Tidak ada produk untuk diproses.'], 400);
            }

            $total = 0;
            $itemDetails = [];
            $orders = [];

            // **Gunakan satu order_id untuk semua transaksi dalam checkout ini**
            $masterOrderId = 'ORDER-' . strtoupper(Str::random(10));
            Log::info('Generated Global Order ID:', ['order_id' => $masterOrderId]);

            // Ambil semua produk berdasarkan ID dalam satu query dan pastikan ID dalam bentuk integer
            $productIds = collect($checkoutProducts)
                ->pluck('product_id')
                ->map(fn($id) => (int) $id)
                ->toArray();

            Log::info('Daftar ID Produk yang dicari:', ['productIds' => $productIds]);

            // Ambil produk dari DB dan buat associative array dengan key = id
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            Log::info('Produk yang ditemukan:', ['products' => $products->toArray()]);

            if ($products->isEmpty()) {
                Log::error('Produk tidak ditemukan dalam database.', ['productIds' => $productIds]);
                return response()->json(['error' => 'Produk tidak ditemukan di database.'], 400);
            }

            // Group checkout products berdasarkan seller_id dari data produk di DB
            $groupedBySeller = collect($checkoutProducts)
                ->groupBy(fn($item) => $products[$item['product_id']]->seller_id ?? null);

            foreach ($groupedBySeller as $sellerId => $sellerItems) {
                if ($sellerId === null) {
                    return response()->json(['error' => 'Ada produk yang tidak valid'], 400);
                }

                $sellerTotal = 0; // Total harga per seller

                // Iterasi hanya item yang terkait dengan seller ini
                foreach ($sellerItems as $index => &$productData) {
                    $productId = (int) $productData['product_id'];

                    // Ambil produk dari data DB yang sudah di-fetch
                    if (!isset($products[$productId])) {
                        Log::error('Produk tidak ditemukan di database', ['product_id' => $productId]);
                        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 400);
                    }

                    $product = $products[$productId];

                    // Tambahkan informasi produk ke data request
                    $productData['name'] = $product->name;
                    $productData['price'] = $product->price;

                    // Hitung subtotal produk (harga * quantity)
                    $subtotal = $product->price * $productData['quantity'];

                    $itemDetails[] = [
                        'id' => 'PROD-' . $product->id,
                        'price' => $product->price,
                        'quantity' => $productData['quantity'],
                        'name' => $product->name,
                    ];

                    // Proses topping jika ada
                    if (!empty($productData['toppings']) && is_array($productData['toppings'])) {
                        foreach ($productData['toppings'] as $toppingIndex => &$topping) {
                            if ((int) ($topping['quantity'] ?? 0) > 0) {
                                $subtotal += ($topping['price'] ?? 0) * ($topping['quantity'] ?? 0);

                                $itemDetails[] = [
                                    'id' => isset($topping['id']) ? 'TOPPING-' . $topping['id'] : 'TOPPING-' . ($toppingIndex + 1),
                                    'price' => (int) ($topping['price'] ?? 0),
                                    'quantity' => (int) ($topping['quantity'] ?? 0),
                                    'name' => 'Topping: ' . ($topping['name'] ?? 'Unknown Topping'),
                                ];
                            }
                        }
                    }

                    $sellerTotal += $subtotal;

                    // Simpan transaksi per produk untuk seller ini
                    $transaction = Transaction::create([
                        'buyer_id' => Auth::id(),
                        'seller_id' => $sellerId,
                        'order_id' => $masterOrderId,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                        'toppings' => json_encode($productData['toppings'] ?? []),
                        'total_price' => $subtotal,
                        'status' => 'pending',
                        'payment_method' => null, // Midtrans update setelah pembayaran
                        'note' => $productData['note'] ?? null // ✅ Simpan catatan ke database
                    ]);

                    $orders[] = $transaction;
                }
            }

            Log::info('Transaksi berhasil disimpan ke database:', ['transactions' => $orders]);

            // Midtrans Checkout untuk semua transaksi
            $midtransParams = [
                'transaction_details' => [
                    'order_id' => $masterOrderId,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('checkout.payment.success', ['order_id' => $masterOrderId]), // Pakai route()
                ],
            ];

            Log::info('Parameter Midtrans:', ['midtransParams' => $midtransParams]);

            $snapToken = Snap::getSnapToken($midtransParams);
            Log::info('Snap Token berhasil dibuat:', ['snap_token' => $snapToken]);

            return response()->json(['success' => true, 'snap_token' => $snapToken]);

        } catch (\Exception $e) {
            Log::error('Error di checkout process:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function receipt($order_id)
    {
        $orders = Transaction::where('order_id', $order_id)->with('product')->get();
        ;

        if (!$orders) {
            return redirect()->route('checkout.index')->with('error', 'Pesanan tidak ditemukan.');
        }

        // Mapping data produk supaya lebih fleksibel di Blade
        $products = $orders->map(function ($order) {
            return [
                'name' => $order->product->name,
                'price' => $order->product->price,
                'quantity' => $order->quantity,
                'toppings' => json_decode($order->toppings, true) ?? [],
            ];
        });

        // Cek apakah ini checkout banyak produk (keranjang) atau hanya 1 produk
        // $isMultipleCheckout = is_null($order->product_id);
        // dd($order->toppings);

        return view('buyer.checkout.receipt', compact('orders', 'products'));
    }

    public function paymentSuccess(Request $request)
    {
        $order_id = $request->query('order_id'); // Ambil order_id dari query parameter

        if (!$order_id) {
            return redirect()->route('checkout.index')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('buyer.checkout.payment_success', compact('order_id'));
    }


    // public function paymentSuccess(Request $request)
    // {
    //     try {
    //         Log::info('Midtrans Callback:', $request->all());
    //         // 🔍 Log request dari Midtrans
    //         Log::info('Received payment success callback:', ['request' => $request->all()]);

    //         // Cek apakah transaksi dengan order_id ini ada
    //         $order = Transaction::where('order_id', $request->order_id)->first();

    //         if (!$order) {
    //             Log::error('Payment failed: Order tidak ditemukan.', ['order_id' => $request->order_id]);
    //             return redirect()->route('checkout.index')->with('error', 'Pesanan tidak ditemukan.');
    //         }

    //         // Cek apakah pesanan sudah berstatus 'paid' sebelumnya
    //         if ($order->status === 'paid') {
    //             Log::warning('Payment ignored: Order sudah dibayar sebelumnya.', ['order_id' => $order->order_id]);
    //             return redirect()->route('checkout.index')->with('error', 'Pesanan sudah diproses sebelumnya.');
    //         }

    //         // Update status transaksi menjadi 'paid' dan set order_status ke 'processing'
    //         $order->update([
    //             'status' => 'paid',
    //             'order_status' => 'processing' // Order otomatis masuk ke 'Processing'
    //         ]);
    //         Log::info('Order updated to paid & status processing:', ['order_id' => $order->order_id]);

    //         // 🔍 Ambil produk yang dipesan
    //         $product = Product::find($order->product_id);
    //         if (!$product) {
    //             Log::error('Produk tidak ditemukan saat update stok.', ['product_id' => $order->product_id]);
    //             return redirect()->route('checkout.index')->with('error', 'Produk tidak ditemukan.');
    //         }

    //         // 🔥 Kurangi stok produk berdasarkan jumlah yang dibeli
    //         $newStock = max(0, $product->stock - $order->quantity); // Stok gak boleh minus
    //         Log::info('Stock sebelum dikurangi:', ['product_id' => $product->id, 'stock' => $product->stock]);

    //         $product->update(['stock' => $newStock]);
    //         Log::info('Stock berhasil dikurangi:', ['product_id' => $product->id, 'new_stock' => $newStock]);

    //         return view('buyer.checkout.payment_success', [
    //             'order_id' => $order->order_id
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error di paymentSuccess:', ['message' => $e->getMessage()]);
    //         return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan dalam memproses pembayaran.');
    //     }
    // }

    public function directCheckout(Request $request)
    {
        try {
            Log::info('Data dari request checkout:', ['request' => $request->all()]);

            $request->validate([
                'checkoutProducts' => 'required|array',
                'checkoutProducts.*.product_id' => 'required|exists:products,id',
                'checkoutProducts.*.quantity' => 'required|integer|min:1',
                'checkoutProducts.*.toppings' => 'array',
            ]);

            $products = [];

            foreach ($request->checkoutProducts as $productData) {
                $product = Product::find($productData['product_id']);
                if (!$product) {
                    continue;
                }

                $toppings = json_decode($product->toppings, true) ?? [];
                foreach ($toppings as &$topping) {
                    $topping['quantity'] = 0; // Default quantity 0, nanti bisa di-update di frontend
                }

                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => (int) $productData['quantity'],
                    'toppings' => $toppings,
                ];
            }

            session()->put('checkoutProducts', $products);
            session()->save();

            return response()->json([
                'success' => true,
                'redirect' => route('checkout.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error directCheckout:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
