<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailChangeController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $user = Auth::user();

        $resetLink = route('profile.email.reset', [
            'token' => base64_encode($user->email),
        ]);

        Mail::send('emails.reset-email', ['resetLink' => $resetLink], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Email Link');
        });

        return response()->json(['success' => true]);
    }

    public function showResetForm(Request $request)
    {
        $email = base64_decode($request->token);

        return view('buyer.profile.reset-email', ['email' => $email]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah!']);
        }

        $user->email = $request->new_email;
        $user->save();

        return redirect()->route('buyer.dashboard')->with('success', 'Email berhasil diubah!');
    }
}
