<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant-specific Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file is specific to tenant settings and features.
    |
    */

    'features' => [
        'teachers' => [
            'enabled' => true,
            'max_teachers' => env('TENANT_MAX_TEACHERS', 50),
            'default_password' => env('TENANT_DEFAULT_PASSWORD', 'password'),
        ],
        'students' => [
            'enabled' => true,
            'max_students' => env('TENANT_MAX_STUDENTS', 1000),
        ],
        'classes' => [
            'enabled' => true,
            'max_classes' => env('TENANT_MAX_CLASSES', 50),
        ],
    ],

    'roles' => [
        'school_admin' => [
            'name' => 'School Administrator',
            'permissions' => ['*'],
        ],
        'teacher' => [
            'name' => 'Teacher',
            'permissions' => [
                'view_classes',
                'manage_students',
                'manage_assignments',
                'manage_grades',
            ],
        ],
        'student' => [
            'name' => 'Student',
            'permissions' => [
                'view_classes',
                'view_assignments',
                'submit_assignments',
                'view_grades',
            ],
        ],
    ],

    'storage' => [
        'max_file_size' => env('TENANT_MAX_FILE_SIZE', 10240), // in KB
        'allowed_file_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
    ],

    'notifications' => [
        'email' => [
            'enabled' => true,
            'from_address' => env('TENANT_MAIL_FROM_ADDRESS', 'noreply@example.com'),
            'from_name' => env('TENANT_MAIL_FROM_NAME', 'School Management System'),
        ],
    ],
];
