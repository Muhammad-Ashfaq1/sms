<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Assuming you have a User model
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define users with their roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'role' => 'super_admin',
            ],
            [
                'name' => 'School Admin',
                'email' => 'schooladmin@example.com',
                'role' => 'school_admin',
            ],
            [
                'name' => 'Teacher',
                'email' => 'teacher@example.com',
                'role' => 'teacher',
            ],
            [
                'name' => 'Student',
                'email' => 'student@example.com',
                'role' => 'student',
            ],
            [
                'name' => 'Parent',
                'email' => 'parent@example.com',
                'role' => 'parents',
            ],
            [
                'name' => 'NT Staff',
                'email' => 'ntstaff@example.com',
                'role' => 'nt-staff',
            ],
        ];

        // Loop through users and create each user with assigned role
        foreach ($users as $user) {
            // Create user and assign role
            $userInstance = User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                ]
            );
            $userInstance->assignRole($userInstance['role']);
        }
    }
}
