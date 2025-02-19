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
    protected $signature = 'sms:permission-sync {--tenant=}';

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
        $tenant = $this->option('tenant');
        tenancy()->runForMultiple($tenant, function () {
            // Define the guard
            $guardName = 'tenant';

            // Permissions with human-readable labels
            $permissions = [
                // User management permissions
                ['name' => 'users.create', 'display_name' => 'Create Users'],
                ['name' => 'users.view', 'display_name' => 'View Users'],
                ['name' => 'users.update', 'display_name' => 'Update Users'],
                ['name' => 'users.delete', 'display_name' => 'Delete Users'],

                // Role management permissions
                ['name' => 'roles.create', 'display_name' => 'Create Roles'],
                ['name' => 'roles.view', 'display_name' => 'View Roles'],
                ['name' => 'roles.update', 'display_name' => 'Update Roles'],
                ['name' => 'roles.delete', 'display_name' => 'Delete Roles'],

                // Student management permissions
                ['name' => 'students.create', 'display_name' => 'Create Students'],
                ['name' => 'students.view', 'display_name' => 'View Students'],
                ['name' => 'students.update', 'display_name' => 'Update Students'],
                ['name' => 'students.delete', 'display_name' => 'Delete Students'],

                // Teacher management permissions
                ['name' => 'teachers.create', 'display_name' => 'Create Teachers'],
                ['name' => 'teachers.view', 'display_name' => 'View Teachers'],
                ['name' => 'teachers.update', 'display_name' => 'Update Teachers'],
                ['name' => 'teachers.delete', 'display_name' => 'Delete Teachers'],
            ];

            // Create permissions
            foreach ($permissions as $permissionData) {
                Permission::updateOrCreate(
                    ['name' => $permissionData['name'], 'guard_name' => $guardName],
                    ['label' => $permissionData['display_name']]
                );
            }

            // Create roles and assign permissions
            $roles = [
                RoleEnum::ADMIN->value => $permissions,
                'teacher' => [
                    'students.view',
                    'teachers.view',
                ],
                'student' => [
                    'students.view',
                ],
                'parent' => [
                    'students.view',
                ],
                'nt-staff' => [
                    'students.view',
                    'teachers.view',
                ],
            ];

            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guardName]);
                $role->syncPermissions(is_array($rolePermissions) ? array_column($rolePermissions, 'name') : $rolePermissions);
            }
        });
    }
}
