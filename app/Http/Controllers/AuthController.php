<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => 'customer'
        ]);

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->roles === 'admin') {
                return redirect('dashboard');
            } else {
                return redirect('/user');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('status', 'email_not_found');
        }

        if ($user->roles === 'admin') {
            return back()->with('status', 'admin_reset_error');
        }

        // Generate a token and insert into password_resets table
        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Log the reset password link
        $resetLink = route('password.reset.form', ['token' => $token, 'email' => $request->email]);
        Log::info("Reset Password Link for {$request->email}: $resetLink");

        return back()->with('status', 'Password reset link has been sent to your email. Please check the logs for the reset link.');
    }


    public function showResetPasswordForm($token, Request $request)
    {
        $reset = DB::table('password_resets')->where('token', $token)->first();

        if (!$reset) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid token']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $reset->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:3|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$reset) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid token']);
        }

        $user = User::where('email', $reset->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email not found']);
        }

        if ($user->roles === 'admin') {
            return redirect()->back()->withErrors(['email' => 'Admin cannot reset password']);
        }

        // Update user password
        $user->update(['password' => Hash::make($request->password)]);

        // Remove reset token from database
        DB::table('password_resets')->where('email', $reset->email)->delete();

        return redirect()->route('login')->with('status', 'Password has been reset!');
    }
}
