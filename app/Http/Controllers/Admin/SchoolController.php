<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SetupSchoolDatabaseJob;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'domain' => 'required|string|unique:schools,domain',
            'admin_email' => 'required|email|unique:users,email',
            'status' => 'required|boolean',
        ]);

        // Create school record
        $school = School::create([
            'name' => $request->name,
            'address' => $request->address,
            'domain' => $request->domain,
            'database' => 'tenant_'.strtolower(str_replace(' ', '_', $request->name)),
            'admin_email' => $request->admin_email,
            'status' => $request->status,
        ]);

        // Dispatch job to set up tenant database
        SetupSchoolDatabaseJob::dispatch($school);

        // Create the admin user and assign role
        $admin = User::create([
            'name' => $request->name.' Admin',
            'email' => $request->admin_email,
            'password' => 'password', // Default password (hashing will occur in the model)
        ]);

        $admin->assignRole('school_admin');

        return redirect()->back()->with('success', 'School created successfully, and setup is in progress.');
    }
}
