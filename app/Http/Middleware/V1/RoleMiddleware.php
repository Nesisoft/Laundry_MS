<?php

namespace App\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Unauthorized'
            ], 401);
        }

        if (auth()->user()->hasRole($role)) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error', 
            'message' => 'Access denied'
        ], 403);
    }
}