<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the Admin Dashboard.
     */
    public function index()
    {
        return view('admin.index'); // Pastikan view ini ada
    }
}
