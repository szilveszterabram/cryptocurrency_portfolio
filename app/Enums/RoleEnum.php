<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case GUEST = 'guest';

    public function label(): string
    {
        return match ($this) {
            RoleEnum::USER => 'User',
            RoleEnum::ADMIN => 'Admin',
            RoleEnum::GUEST => 'Guest',
        };
    }
}
