<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guestRole = Role::findByName(RoleEnum::GUEST->value);
        $userRole = Role::findByName(RoleEnum::USER->value);

        $permissions = Permission::all();
        $guestPermissions = Permission::whereIn('name', [
            'navigate to welcome',
            'navigate to profile',
            'navigate to assets'
        ])->get();

        $userRole->givePermissionTo($permissions);
        $guestRole->givePermissionTo($guestPermissions);
    }
}
