<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->role || !$user->role->is_active) {
            return response()->json([
                'message' => 'El rol asignado a esta cuenta se encuentra inactivo.',
            ], 403);
        }
    
        return $next($request);
    }
}
