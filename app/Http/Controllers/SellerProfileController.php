<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SellerProfileController extends Controller
{
    public function index()
    {
        return view('seller.profile.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        $user = auth()->user();

        // Update nama
        $user->name = $request->name;

        // Update profile_image jika ada file baru
        if ($request->hasFile('profile_image')) {
            // Hapus gambar lama jika ada
            if ($user->profile_image) {
                \Storage::delete($user->profile_image);
            }

            // Simpan gambar baru
            $path = $request->file('profile_image')->store('avatars', 'public');
            $user->profile_image = $path;
        }

        // Jika ada input current_password, cek validitasnya
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah']);
            }

            // Jika valid, ganti password dengan yang baru
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
        }

        $user->save();

        // Tambahkan flash message
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
