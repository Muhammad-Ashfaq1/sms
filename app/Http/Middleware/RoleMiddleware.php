<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        } else {
            dd($request->all());
        }

        return $next($request);
    }
}
