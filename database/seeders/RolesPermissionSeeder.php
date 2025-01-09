<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = [
            'super_admin',
            'school_admin',
            'Admin',
            'teacher',
            'student',
            'parents',
            'nt-staff'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create permissions and assign to roles as needed
        // Add your permission logic here
    }
}
