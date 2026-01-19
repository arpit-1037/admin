<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // User must be authenticated
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // User must have the required role
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
