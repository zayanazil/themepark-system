<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        //must be logged in
        if (!auth()->check()) {
            return redirect('/login');
        }

        //check if user has any allowed role
        if (!in_array(auth()->user()->role, $roles)) {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        return $next($request);
    }
}
