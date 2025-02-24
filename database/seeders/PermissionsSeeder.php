<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tenancy()->runForMultiple(null, function () {

            // Define the guard
            $guardName = 'tenant';

            // Permissions with human-readable labels
            $permissions = [
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
            $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => $guardName]);

            // Assign all permissions to Admin Role
            $adminRole->syncPermissions(array_column($permissions, 'name'));
        });
    }
}
