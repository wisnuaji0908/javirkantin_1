<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBlocked
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_blocked) {
            Auth::logout();
            return redirect()->route('blocked')->with('error', 'Akun Anda telah diblokir. Silakan hubungi admin.');
        }

        return $next($request);
    }
}
