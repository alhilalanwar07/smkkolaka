<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowedRoles = $roles !== [] ? $roles : ['admin'];

        if (! $request->user() || ! $request->user()->hasAnyRole($allowedRoles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
