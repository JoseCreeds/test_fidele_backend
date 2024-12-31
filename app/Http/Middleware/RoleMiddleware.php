<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //check if user authentificated
        if (!auth()->check()) {
            return (response()->json(['message' => 'Unauthorized'], 401));
        }

        //check if user has required role
        if (auth()->user()->role !== $role) {
            return (response()->json(['message' => 'Forbidden'], 403));
        }

        return $next($request);
    }
}
