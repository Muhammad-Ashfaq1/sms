<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if we're on a tenant domain
        $host = $request->getHost();
        $school = School::where('domain', $host)->first();

        if ($school) {
            // Initialize tenancy for the school
            tenancy()->initialize($school->tenant_id);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is super admin and redirect accordingly
            if (Auth::user()->hasRole('super_admin')) {
                return redirect()->route('schools.index');
            }

            return redirect()->intended('home')->with('success', 'Successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
