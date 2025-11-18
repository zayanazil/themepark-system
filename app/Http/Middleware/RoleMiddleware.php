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
        $user = Auth::user();

        // 1. Check if user is logged in
        if (!$user) {
            return redirect('/login');
        }

        // 2. The Fix: Allow access if the roles match OR if the user is an 'admin'
        if ($user->role === $role || $user->role === 'admin') {
            return $next($request);
        }

        // 3. Otherwise, block access
        abort(403, 'Unauthorized action. You do not have the ' . $role . ' role.');
    }
}
