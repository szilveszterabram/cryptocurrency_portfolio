<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case User = 'user';
    case Guest = 'guest';

    public function label(): string
    {
        return match ($this) {
            RoleEnum::User => 'User',
            RoleEnum::Admin => 'Admin',
            RoleEnum::Guest => 'Guest',
        };
    }
}
