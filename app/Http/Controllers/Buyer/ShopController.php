<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        // Ambil semua seller dengan role 'seller'
        $sellers = User::where('role', 'seller')->get();
        return view('buyer.shop.index', compact('sellers'));
    }

    // ShopController.php
    public function products($sellerId)
    {
        // Ambil seller berdasarkan ID
        $seller = User::findOrFail($sellerId);

        // Ambil produk berdasarkan seller_id
        $products = Product::where('seller_id', $sellerId)->get();

        return view('buyer.shop.products', compact('seller', 'products'));
    }
}
