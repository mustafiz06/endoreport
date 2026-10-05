<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Required role: ' . implode(' or ', $roles),
                ], 403);
            }

            abort(403, 'Unauthorized. Access restricted to role: ' . implode(' or ', $roles) . '.');
        }

        return $next($request);
    }
}
