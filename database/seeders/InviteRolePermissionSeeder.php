<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InviteRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::findByName(RoleEnum::ADMIN->value);
        $permissions = Permission::whereIn('name', [
            'navigate to invite',
            'send an invite at invite.invite'
        ]);

        $adminRole->givePermissionTo($permissions);
    }
}
