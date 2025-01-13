<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Show the Buyer Dashboard.
     */
    public function index()
    {
        return view('buyer.index'); // Pastikan view ini ada
    }
}
