<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show the Admin Dashboard.
     */
    public function index()
    {
        return view('admin.index'); // Pastikan view ini ada
    }

    /**
     * Show the list of sellers.
     */
    public function showSellers(Request $request)
    {
        // Ambil query untuk filter
        $query = User::where('role', 'seller');

        // Filter berdasarkan nama
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter berdasarkan email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Pagination data seller
        $sellers = $query->paginate(10);

        // Kirim data ke view
        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Show the form to create a new seller.
     */
    public function createSeller()
    {
        return view('admin.sellers.create');
    }

    /**
     * Store a newly created seller in the database.
     */
    public function storeSeller(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $generatedPassword = Str::random(8); // Generate password otomatis

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role' => 'seller',
            'email_verified_at' => now(), // Tandai email langsung terverifikasi
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Seller berhasil ditambahkan.');
    }

    /**
     * Show the form to edit a seller.
     */
    public function editSeller($id)
    {
        $seller = User::findOrFail($id);
        return view('admin.sellers.edit', compact('seller'));
    }

    /**
     * Update the specified seller in the database.
     */
    public function updateSeller(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $seller = User::findOrFail($id);
        $seller->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Seller berhasil diperbarui.');
    }

    /**
     * Remove the specified seller from the database.
     */
    public function deleteSeller($id)
    {
        $seller = User::find($id);
        if (!$seller) {
            return redirect()->route('admin.sellers.index')->with('error', 'Seller tidak ditemukan.');
        }

        $seller->delete();
        return redirect()->route('admin.sellers.index')->with('success', 'Seller berhasil dihapus.');
    }

    public function listBuyers(Request $request)
    {
        $query = User::where('role', 'buyer');

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Paginate buyers
        $buyers = $query->paginate(10);

        return view('admin.buyers.index', compact('buyers'));
    }

    public function toggleBlockBuyer($id)
    {
        $buyer = User::findOrFail($id);

        $buyer->update([
            'is_blocked' => !$buyer->is_blocked,
        ]);

        \Log::info("Buyer ID {$id} status updated to: " . ($buyer->is_blocked ? 'Blocked' : 'Active'));

        $status = $buyer->is_blocked ? 'diblokir' : 'diaktifkan';
        return redirect()->route('admin.buyers.index')->with('success', "Buyer berhasil $status.");
    }
}
