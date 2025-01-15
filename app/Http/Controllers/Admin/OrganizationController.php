<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SetupTenantJob;

class OrganizationController extends Controller
{
    public $domain;

    public \App\Models\User $user;

    public function index()
    {
        $tenants = Tenant::all();
        return view('admin.organization.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.organization.create');
    }

    public function storeOrganization(StoreOrganizationRequest $request)
    {
        $tenantId = generateUniqueTenant();

        $tenant = Tenant::create([
            'id' => str_replace('-', '_', $tenantId),
            'org_name' => $request->org_name,
            'email' => $request->email,
            'status' => $request->status,
            'address' => $request->address,
        ]);

        $pattern = '/(?:https?:\/\/)?([a-zA-Z0-9-]+)/';
        preg_match($pattern, $request->domain, $domain);
        $tenant->domains()->create(['domain' => "{$domain[1]}.".centralDomain()]);

        $user = [
            'email' => $request->email,
            'password' => Hash::make('password'), // Generate password securely
        ];
        SetupTenantJob::dispatch($tenant, $user);

        return $this->latestRecords();
    }

    public function edit($tenant)
    {
        $tenant = Tenant::whereId($tenant)->with('domains')->first();
        return response()->json($tenant);
    }

    public function update(Request $request, $tenantId)
    {
        $tenant = Tenant::whereId($tenantId)->first();

        $tenant->update([
            'org_name' => $request->org_name,
            'email' => $request->email,
            'status' => $request->status,
            'address' => $request->address,
        ]);
        if ($request->domain) {
            $tenant->domains()->updateOrCreate(
                ['tenant_id' => $tenantId],
                ['domain' => $request->domain]
            );
        }

        return $this->latestRecords();
    }


    public function destroy($tenantId)
    {
        Tenant::destroy($tenantId);
        return $this->latestRecords();
    }

    private function latestRecords(){
        return response()->json([
            'html' => view('admin.organization.datable', ['tenants' => Tenant::all()])->render()
        ]);
    }

}

