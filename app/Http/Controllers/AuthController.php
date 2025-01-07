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
            'role' => 'customer',
        ]);

        // Send Verification Email
        $verificationLink = route('verify.email', ['id' => $user->id]);
        Mail::raw("Click the following link to verify your email: $verificationLink", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verify Your Email');
        });

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

            if ($user->role === 'admin') {
                return redirect('dashboard');
            } else {
                return redirect('/user');
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

        $resetLink = route('password.reset.form', ['token' => $token]);
        Mail::raw("Reset your password using the following link: $resetLink", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Your Password');
        });

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
