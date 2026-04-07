<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $roles = array_map(
            static fn ($value) => strtolower(trim($value)),
            $roles
        );

        $userRole = strtolower(trim((string) optional($request->user())->role));

        if (!$request->user() || !in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
