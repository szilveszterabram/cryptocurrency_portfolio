<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    public function __construct()
    {
    }

    public function getAllUsers(): LengthAwarePaginator
    {
        return User::with('roles')->paginate();
    }

    public function getUserWithRoles(int $id): User
    {
        return User::with('roles')->findOrFail($id);
    }

    public function getUser(int $id): User
    {
        return User::findOrFail($id);
    }

    public function deleteUser(int $id): void
    {
        User::findOrFail($id)->delete();
    }

    public function updateUser(int $id, array $data): void
    {
        $user = User::findOrFail($id);
        $user->update($data);
    }
}
