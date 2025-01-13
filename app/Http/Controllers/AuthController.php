<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\CustomEmail;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Process Registration
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
        ]);

        // Kirim email verifikasi menggunakan CustomEmail
        $verificationLink = route('verify.email', ['id' => $user->id]);
        $emailData = [
            'name' => $user->name,
            'email' => $user->email,
            'reset_link' => $verificationLink,
        ];

        Mail::to($user->email)->send(new CustomEmail($emailData, $user, $verificationLink));

        return redirect()->route('login')->with('success', 'Account created. Please check your email to verify.');
    }

    // Verify Email
    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Email verified successfully.');
    }

    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Process Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Please verify your email first.']);
            }

            // Redirect Based on Role
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'penjual':
                    return redirect()->route('seller.dashboard');
                case 'pembeli':
                    return redirect()->route('buyer.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['role' => 'Invalid role!']);
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Send Reset Link Email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('status', 'Email not found.');
        }

        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetLink = route('password.reset.form', [
            'token' => $token,
            'email' => $user->email, // Tambahkan email ke query string
        ]);

        // Tambahkan data yang akan digunakan di email
        $data = [
            'name' => $user->name, // Nama user
            'email' => $user->email, // Email user
            'reset_link' => $resetLink, // Link reset password
        ];

        Mail::to($user->email)->send(new CustomEmail($data, $user, $resetLink));

        return back()->with('status', 'Password reset link sent to your email.');
    }

    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Process Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:3|confirmed',
            'token' => 'required',
        ]);

        $reset = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$reset) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid token']);
        }

        $user = User::where('email', $reset->email)->first();

        $user->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where('email', $reset->email)->delete();

        return redirect()->route('login')->with('status', 'Password has been reset!');
    }

    // QR Login
    public function qrLogin($token)
    {
        $user = User::where('qr_login_token', $token)->first();

        if ($user) {
            Auth::login($user);

            if ($user->role === 'admin') {
                return redirect()->route('dashboard.home');
            } elseif ($user->role === 'customer') {
                return redirect()->route('user.index');
            }
        }

        return redirect()->route('login')->withErrors(['Invalid or expired QR code']);
    }
}
