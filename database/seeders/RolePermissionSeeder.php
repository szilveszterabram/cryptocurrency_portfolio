<?php

namespace Database\Seeders;

use App\Enums\AssetPermissionEnum;
use App\Enums\HomePermissionEnum;
use App\Enums\ProfilePermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guestRole = Role::findByName(RoleEnum::Guest->value);
        $userRole = Role::findByName(RoleEnum::User->value);

        $permissions = Permission::all();
        $guestPermissions = Permission::whereIn('name', [
            HomePermissionEnum::NavigateToWelcome->value,
            ProfilePermissionEnum::NavigateToProfile->value,
            AssetPermissionEnum::NavigateToAsset->value
        ])->get();

        $userRole->givePermissionTo($permissions);
        $guestRole->givePermissionTo($guestPermissions);
    }
}
