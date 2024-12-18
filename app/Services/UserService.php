<?php

namespace App\Services;

use App\Models\User;
use \Illuminate\Foundation\Auth\User as AuthUser;

class UserService
{
    public function __construct(protected User $user) {}

    public function getById(int $id): AuthUser
    {
        return AuthUser::findOrFail($id);
    }
}
