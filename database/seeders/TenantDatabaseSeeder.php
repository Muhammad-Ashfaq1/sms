<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TenantDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create roles for tenant
        $roles = [
            'school_admin' => [
                'manage_teachers',
                'manage_students',
                'manage_classes',
                'manage_attendance',
                'manage_grades',
            ],
            'teacher' => [
                'view_classes',
                'manage_students',
                'manage_attendance',
                'manage_grades',
            ],
            'student' => [
                'view_classes',
                'view_grades',
                'view_attendance',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            // Create role
            $role = Role::create(['name' => $roleName]);

            // Create and assign permissions
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
