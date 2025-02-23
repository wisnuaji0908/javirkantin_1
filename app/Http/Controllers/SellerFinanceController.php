<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class SellerFinanceController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id(); // Ambil ID seller yang sedang login

        // Total Pendapatan (Total transaksi sukses)
        $totalPendapatan = Transaction::where('seller_id', $sellerId)
            ->where('order_status', 'finish')
            ->sum('total_price');

        // Total Produk Terjual (Semua transaksi selain pending & failed)
        $totalProdukTerjual = Transaction::where('seller_id', $sellerId)
            ->whereNotIn('status', ['pending', 'failed'])
            ->sum('quantity');

        // Total Transaksi Sukses (order_status = 'finish')
        $totalTransaksiSukses = Transaction::where('seller_id', $sellerId)
            ->where('order_status', 'finish')
            ->count();

        // Daftar Transaksi Sukses dengan pagination (order_status = 'finish')
        $transaksiSukses = Transaction::where('seller_id', $sellerId)
            ->where('order_status', 'finish')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.index', compact(
            'totalPendapatan',
            'totalProdukTerjual',
            'totalTransaksiSukses',
            'transaksiSukses'
        ));
    }
}
