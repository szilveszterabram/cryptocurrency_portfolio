<?php

namespace Database\Seeders;

use App\Enums\AdminPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = [
            AdminPermissionEnum::Navigate->value,
            AdminPermissionEnum::ViewUser->value,
            AdminPermissionEnum::MakeUserAdmin->value,
            AdminPermissionEnum::UpdateUser->value,
            AdminPermissionEnum::DeleteUser->value,
        ];

        foreach ($admin as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}

