<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common attributes for all roles
        $commonAttributes = [
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $roles = [
            'super_admin',
            'school_admin',
            'teacher',
            'student',
            'parents',
            'nt-staff',
        ];

        // Loop through the roles and update or insert each one
        foreach ($roles as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName],
                array_merge($commonAttributes, ['name' => $roleName])
            );
        }
    }
}
