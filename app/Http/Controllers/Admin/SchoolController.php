<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SetupSchoolDatabaseJob;
use App\Models\School;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

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

        try {
            DB::beginTransaction();

            // Create tenant
            $tenant = Tenant::create([
                'id' => Str::slug($request->domain),
                'tenancy_db_name' => 'tenant_' . Str::slug($request->name),
            ]);

            // Create domain for tenant
            $tenant->domains()->create([
                'domain' => $request->domain,
            ]);

            // Create school record
            $school = School::create([
                'name' => $request->name,
                'address' => $request->address,
                'domain' => $request->domain,
                'database' => 'tenant_' . Str::slug($request->name),
                'admin_email' => $request->admin_email,
                'tenant_id' => $tenant->id,
                'status' => $request->status,
            ]);

            // Create the school admin user
            $admin = User::create([
                'name' => $request->name . ' Admin',
                'email' => $request->admin_email,
                'password' => Hash::make('password123'),
            ]);

            DB::commit();

            // Dispatch job after commit
            SetupSchoolDatabaseJob::dispatch($school)->afterCommit();

            return redirect()->route('schools.index')
                ->with('success', 'School created successfully. Admin can login at http://' . $request->domain . ':8000 with:' .
                    "\nEmail: " . $request->admin_email .
                    "\nPassword: password123");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create school. ' . $e->getMessage()]);
        }
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $school->update($request->only(['name', 'address', 'status']));

        return redirect()->route('schools.index')
            ->with('success', 'School updated successfully.');
    }
}
