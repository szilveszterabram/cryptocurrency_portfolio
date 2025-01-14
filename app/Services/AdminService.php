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
}
