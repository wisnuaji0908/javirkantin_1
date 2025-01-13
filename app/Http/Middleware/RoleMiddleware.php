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

        if ($userRole !== $role) {
            switch ($userRole) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'penjual':
                    return redirect()->route('seller.dashboard');
                case 'pembeli':
                    return redirect()->route('buyer.dashboard');
                default:
                    \Log::info("Role mismatch: User role is $userRole, required role is $role");
                    return abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
