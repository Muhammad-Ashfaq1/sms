<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
    case PARENT = 'parent';
    case NT_STAFF = 'nt-staff';

    public function label(): string
    {
        return match ($this) {
            RoleEnum::ADMIN => 'Admin',
            RoleEnum::TEACHER => 'Teacher',
            RoleEnum::STUDENT => 'Student',
            RoleEnum::PARENT => 'Parent',
            RoleEnum::NT_STAFF => 'NT Staff',
        };
    }
}
