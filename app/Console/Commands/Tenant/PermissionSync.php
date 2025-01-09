<?php

namespace App\Console\Commands\Tenant;

use App\Enums\RoleEnum;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiedown:permission-sync {--tenant=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $tenant = $this->option('tenant');
        tenancy()->runForMultiple($tenant, function () {
            // Define the guard
            $guardName = 'tenant';

            // Permissions with human-readable labels
            $permissions = [
                ['name' => 'categories.create', 'display_name' => 'Create Categories'],
                ['name' => 'categories.view', 'display_name' => 'View Categories'],
                ['name' => 'categories.update', 'display_name' => 'Update Categories'],
                ['name' => 'categories.delete', 'display_name' => 'Delete Categories'],

                ['name' => 'customers.create', 'display_name' => 'Create Customers'],
                ['name' => 'customers.view', 'display_name' => 'View Customers'],
                ['name' => 'customers.update', 'display_name' => 'Update Customers'],
                ['name' => 'customers.delete', 'display_name' => 'Delete Customers'],

                ['name' => 'products.create', 'display_name' => 'Create Products'],
                ['name' => 'products.view', 'display_name' => 'View Products'],
                ['name' => 'products.update', 'display_name' => 'Update Products'],
                ['name' => 'products.delete', 'display_name' => 'Delete Products'],

                ['name' => 'users.create', 'display_name' => 'Create Users'],
                ['name' => 'users.view', 'display_name' => 'View Users'],
                ['name' => 'users.update', 'display_name' => 'Update Users'],
                ['name' => 'users.delete', 'display_name' => 'Delete Users'],

                ['name' => 'roles.create', 'display_name' => 'Create Roles'],
                ['name' => 'roles.view', 'display_name' => 'View Roles'],
                ['name' => 'roles.update', 'display_name' => 'Update Roles'],
                ['name' => 'roles.delete', 'display_name' => 'Delete Roles'],
            ];

            // Create permissions
            foreach ($permissions as $permissionData) {
                Permission::updateOrCreate(
                    ['name' => $permissionData['name'], 'guard_name' => $guardName],
                    ['label' => $permissionData['display_name']]
                );
            }

            // Create Admin Role
            $adminRole = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value, 'guard_name' => $guardName]);

            // Assign all permissions to Admin Role
            $adminRole->syncPermissions(array_column($permissions, 'name'));
        });
    }
}
