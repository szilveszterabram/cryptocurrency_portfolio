<?php

namespace Database\Seeders;

use App\Enums\InvitationPermissionEnum;
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
            InvitationPermissionEnum::NavigateToInvite->value,
            InvitationPermissionEnum::SendInvite->value
        ]);

        $adminRole->givePermissionTo($permissions);
    }
}
