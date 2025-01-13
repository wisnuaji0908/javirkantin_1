<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Show the Seller Dashboard.
     */
    public function index()
    {
        return view('seller.index'); // Pastikan view ini ada
    }
}
