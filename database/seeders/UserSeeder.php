<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
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

        foreach ($users as $user) {
            $userInstance = User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $userInstance->assignRole($user['role']);
        }
    }
}
