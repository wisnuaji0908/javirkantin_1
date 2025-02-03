<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Check if user is authenticated
        $this->authenticate($request, $guards);

        // Check if user is blocked
        if (Auth::check() && Auth::user()->is_blocked) {
            Auth::logout();
            return redirect()->route('blocked')->with('error', 'Akun Anda telah diblokir. Silakan hubungi admin.');
        }

        return $next($request);
    }
}
