<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if user is logged in and has the matching role
        if (!Auth::check() || Auth::user()->role !== $role) {
            // If they don't match, send them back or show error
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
