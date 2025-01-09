<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Stancl\Tenancy\Database\Models\Domain;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Get domain from request
        $host = $request->getHost();

        // Check if this is a tenant domain
        $domain = Domain::where('domain', $host)->first();

        if ($domain && $domain->tenant) {
            // Initialize tenancy for the domain
            tenancy()->initialize($domain->tenant);

            // Set the database connection to the tenant's database
            Config::set('database.default', 'tenant');

            // Attempt authentication on tenant database
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // Store tenant info in session
                session(['tenant_id' => $domain->tenant->id]);

                return redirect()->intended(route('tenant.dashboard'));
            }

            // Reset database connection
            Config::set('database.default', 'mysql');
        } else {
            // This is the central domain
            Config::set('database.default', 'mysql');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                if (Auth::user()->hasRole('super_admin')) {
                    return redirect()->intended(route('organization.index'));
                }

                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Get current domain before logout
        $host = $request->getHost();
        $domain = Domain::where('domain', $host)->first();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // If it's a tenant domain, redirect to tenant login
        if ($domain && $domain->tenant) {
            return redirect()->route('login');
        }

        // Otherwise redirect to central domain
        return redirect('/');
    }
}
