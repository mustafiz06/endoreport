<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. You do not possess the required permission: ' . $permission,
                ], 403);
            }

            abort(403, 'Unauthorized. Access requires clinical permission: [' . $permission . '].');
        }

        return $next($request);
    }
}
