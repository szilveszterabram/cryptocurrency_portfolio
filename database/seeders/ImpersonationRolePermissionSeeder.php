<?php

namespace Database\Seeders;

use App\Enums\ImpersonationPermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ImpersonationRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::findByName(RoleEnum::Admin->value);
        $permissions = Permission::whereIn('name', [
            ImpersonationPermissionEnum::ImpersonateUser->value,
            ImpersonationPermissionEnum::ImpersonateUser->value,
        ]);

        $adminRole->givePermissionTo($permissions);
    }
}
