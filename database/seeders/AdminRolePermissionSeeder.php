<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::findByName(RoleEnum::ADMIN->value);
        $permissions = Permission::all();

        $adminRole->givePermissionTo($permissions);
    }
}
