<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // If user is not authenticated, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        // If user is authenticated but doesn't have the required role
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
