<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Jobs\SetupTenantJob;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function storeOrganization(StoreOrganizationRequest $request)
    {
        // Generate a unique tenant ID
        $tenantId = generateUniqueTenant();

        // Create new tenant instance using mass assignment
        $tenant = Tenant::create([
            'id' => str_replace('-', '_', $tenantId),
            'org_name' => $request->org_name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        // Create domain for tenant
        $pattern = '/(?:https?:\/\/)?([a-zA-Z0-9-]+)/';
        preg_match($pattern, $request->domain, $domain);
        $tenant->domains()->create(['domain' => "{$domain[1]}.".centralDomain()]);

        // Prepare user data (admin user)
        $user = [
            'email' => $request->email,
            'password' => Hash::make('password'), // Generate password securely
        ];

        // Dispatch the setup job for tenant (database creation, migrations, roles, etc.)
        SetupTenantJob::dispatch($tenant, $user);

        // Redirect to organization index page after creation
        return redirect(route('organization.index'))->with('success', 'Organization created successfully.');
    }

    public function edit($tenant)
    {
        $tenant = Tenant::whereId($tenant)->first();
        return view('admin.organization.create', compact('tenant'));
    }

    public function update(StoreOrganizationRequest $request, $tenantId)
    {
        $tenant = Tenant::whereId($tenantId);
        $tenant->update($request->validated());
        return redirect()->route('organization.index')->with('success', 'Organization updated successfully.');
    }

}
