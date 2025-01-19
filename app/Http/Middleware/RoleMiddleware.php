<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Cek apakah role sesuai
        if ($userRole !== $role) {
            \Log::info("Role mismatch: User role is $userRole, required role is $role");

            // Redirect ke dashboard berdasarkan role
            return match ($userRole) {
                'admin' => redirect()->route('admin.dashboard'),
                'seller' => redirect()->route('seller.dashboard'),
                'buyer' => redirect()->route('buyer.dashboard'),
                default => abort(403, 'Unauthorized'), // Handle role tidak dikenal
            };
        }

        return $next($request);
    }
}
