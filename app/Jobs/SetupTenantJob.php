<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\Tenant\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class SetupTenantJob implements ShouldQueue
{
    use Queueable;

    protected $tenant;
    protected $user;

    public function __construct(Tenant $tenant, $user)
    {
        $this->tenant = $tenant;
        $this->user = $user;
    }

    public function handle(): void
    {
        try {
            // Run migrations for the tenant
            Log::info('Starting migration for tenant: ' . $this->tenant->id);
            $migrateOutput = Artisan::call('tenants:migrate', ['--tenants' => $this->tenant->id]);
            Log::info('Migrate Output for tenant ' . $this->tenant->id . ': ' . $migrateOutput);

            // Sync permissions for the tenant
            Log::info('Starting permission sync for tenant: ' . $this->tenant->id);
            $syncOutput = Artisan::call('tiedown:permission-sync', ['--tenant' => $this->tenant->id]);
            Log::info('Sync Output for tenant ' . $this->tenant->id . ': ' . $syncOutput);

            // Switch to tenant's database connection
            $tenantConnection = 'tenant_' . $this->tenant->id;  // Assuming each tenant has a separate connection
            config(['database.default' => $tenantConnection]);

            // Create or get the user for the tenant
            Log::info('Creating or fetching user for tenant: ' . $this->tenant->id);

            $this->tenant->run(function () use ($tenantConnection) {
                // Create or find the user by email (from tenant's database)
                $user = User::firstOrCreate(
                    ['email' => $this->user['email']],  // Search by email
                    [
                        'name'     => $this->tenant->org_name,
                        'password' => $this->user['password'],
                    ]
                );

                Log::info('User fetched or created with ID: ' . $user->id . ' for tenant: ' . $this->tenant->id);

                // Ensure the Admin role exists for the 'tenant' guard
                $role = Role::firstOrCreate(
                    ['name' => 'Admin', 'guard_name' => 'tenant'], // Specify the 'tenant' guard
                    ['name' => 'Admin', 'guard_name' => 'tenant']
                );

                Log::info('Role Admin (Guard: tenant) fetched or created.');

                // Assign the role to the user in the tenant's context
                $user->syncRoles([$role->name]);

                Log::info('Admin role assigned to user ID: ' . $user->id . ' for tenant: ' . $this->tenant->id);
            });

            // Reset database connection to central/default after operation
            config(['database.default' => 'mysql']);  // Or set back to your default connection
        } catch (\Exception $e) {
            Log::error('Error in SetupTenantJob for tenant ' . $this->tenant->id . ': ' . $e->getMessage());
        }
    }
}
