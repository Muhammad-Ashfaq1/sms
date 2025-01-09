<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SetupTenantJob;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class OrganizationController extends Controller
{
    public $domain;
    public \App\Models\User $user;

    public function index()
    {
        // Get all tenants
        $tenants = Tenant::all();
        return view('admin.organization.index', compact('tenants'));
    }

    public function create()
    {
        // Return create view
        return view('admin.organization.create');
    }

    public function storeOrganization(Request $request)
    {
        // Validate the request input
        $request->validate([
            'org_name'    => 'required|string|max:255',
            'domain'      => 'required|string|url',
            'admin_email' => 'required|email|unique:users,email',
            'status'      => 'required|in:0,1',
        ]);

        // Generate a unique tenant ID
        $tenantId = generateUniqueTenant();

        // Create new tenant instance using mass assignment
        $tenant = Tenant::create([
            'id'        => str_replace('-', '_', $tenantId) ,
            'org_name'  => $request->org_name,
            'email'     => $request->admin_email,
            'status'    => $request->status,
        ]);

        // Create domain for tenant
        $pattern = '/(?:https?:\/\/)?([a-zA-Z0-9-]+)/';
        preg_match($pattern, $request->domain, $domain);
        $tenant->domains()->create(['domain' => "{$domain[1]}." . centralDomain()]);

        // Prepare user data (admin user)
        $user = [
            'email'    => $request->admin_email,
            'password' => Hash::make('password'), // Generate password securely
        ];

        // Dispatch the setup job for tenant (database creation, migrations, roles, etc.)
        SetupTenantJob::dispatch($tenant, $user);

        // Redirect to organization index page after creation
        return redirect(route('organization.index'))->with('success', 'Organization created successfully.');
    }

}
