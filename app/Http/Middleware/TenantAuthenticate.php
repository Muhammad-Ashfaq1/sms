<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Stancl\Tenancy\Database\Models\Domain;

class TenantAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Get current domain
        $host = $request->getHost();
        $domain = Domain::where('domain', $host)->first();

        if (! $domain || ! $domain->tenant) {
            abort(404);
        }

        // Initialize tenancy if not already initialized
        if (! tenant()) {
            tenancy()->initialize($domain->tenant);
        }

        // Set the database connection to tenant
        Config::set('database.default', 'tenant');

        // Verify user exists in tenant database
        $tenantUser = Auth::guard('tenant')->getProvider()->retrieveById(Auth::id());

        if (! $tenantUser) {
            Auth::logout();
            Config::set('database.default', 'mysql');

            return redirect()->route('login')
                ->withErrors(['email' => 'You do not have access to this domain.']);
        }

        return $next($request);
    }
}
