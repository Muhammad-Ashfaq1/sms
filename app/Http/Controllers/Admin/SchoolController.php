<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SetupSchoolDatabaseJob;
use App\Models\School;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public $domain;

    public \App\Models\Tenant $tenant;

    public \App\Models\User $user;

    public $password;

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
        Log::info('Starting school creation process...'); // Log the start of the process

        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'domain' => 'required|string|unique:schools,domain',
            'admin_email' => 'required|email|unique:users,email',
            'status' => 'required|boolean',
        ]);

        $this->tenant->id = generateUniqueTenant();
        $this->tenant->save();

        $pattern = '/(?:https?:\/\/)?([a-zA-Z0-9-]+)/';
        preg_match($pattern, $this->domain, $domain);
        $this->tenant->domains()->create(['domain' => "{$domain[1]}.".centralDomain()]);

        $user = [
            'email' => $this->user->email,
            'password' => $this->password,
        ];
        //        SetupTenantJob::dispatch($this->tenant,$user);

        try {
            DB::beginTransaction();
            Log::info('Validation passed. Starting tenant creation...');

            // Create tenant
            $tenant = Tenant::create([
                'id' => Str::uuid(), // UUID for tenant ID
                'domain' => $request->domain,
            ]);

            Log::info('Tenant created with ID: '.$tenant->id);

            // Create domain for tenant
            $tenant->domains()->create([
                'domain' => $request->domain,
            ]);

            Log::info('Domain created for tenant: '.$request->domain);

            // Create school record with a placeholder database name (it will be updated in the job)
            $school = School::create([
                'name' => $request->name,
                'address' => $request->address,
                'domain' => $request->domain,
                'database' => 'tenant_'.Str::slug($request->name), // Temporary database name
                'admin_email' => $request->admin_email,
                'tenant_id' => $tenant->id,
                'status' => $request->status,
            ]);

            Log::info('School created with ID: '.$school->id);

            // Create the school admin user
            $admin = User::create([
                'name' => $request->name.' Admin',
                'email' => $request->admin_email,
                'password' => Hash::make('password123'),
            ]);

            Log::info('Admin user created with email: '.$admin->email);

            DB::commit();

            // Dispatch job after commit
            SetupSchoolDatabaseJob::dispatch($school)->afterCommit();

            Log::info('SetupSchoolDatabaseJob dispatched for school ID: '.$school->id);

            return redirect()->route('schools.index')
                ->with('success', 'School created successfully. Admin can login at http://'.$request->domain.':8000 with:'.
                    "\nEmail: ".$request->admin_email.
                    "\nPassword: password123");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error occurred during school creation: '.$e->getMessage());

            return back()->withInput()
                ->withErrors(['error' => 'Failed to create school. '.$e->getMessage()]);
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
