<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and role is admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Otherwise, deny access
        abort(403, 'Unauthorized - Admin access only');
    }
}

