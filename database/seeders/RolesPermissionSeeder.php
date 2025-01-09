<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesPermissionSeeder extends Seeder
{
    public function run()
    {
        // Define the roles and their respective guard_name
        $roles = [
            'super_admin' => 'web',       // Adjust guard as needed
            'school_admin' => 'web',
            'Admin' => 'tenant',          // Example: 'tenant' guard for tenant users
            'teacher' => 'web',
            'student' => 'web',
            'parents' => 'web',
            'nt-staff' => 'web',
        ];

        foreach ($roles as $role => $guard) {
            // Use firstOrCreate to ensure roles are created if they don't exist, and guard_name is applied
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => $guard],  // Find role by name and guard_name
                [
                    'name' => $role,         // The role name
                    'guard_name' => $guard,  // Guard name for tenant or web
                    'created_at' => now(),   // Current timestamp for created_at
                    'updated_at' => now(),   // Current timestamp for updated_at
                ]
            );
        }

        // Optionally, add any permissions for these roles here
        // $permissions = Permission::all();
        // foreach ($roles as $role => $guard) {
        //     $role = Role::where('name', $role)->first();
        //     $role->givePermissionTo($permissions);
        // }
    }
}
