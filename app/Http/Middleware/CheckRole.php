<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Ensure user is logged in natively
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['msg' => 'Please log in first.']);
        }

        // 2. Validate role matches the route requirements
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}