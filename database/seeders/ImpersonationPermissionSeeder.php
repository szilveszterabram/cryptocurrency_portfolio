<?php

namespace Database\Seeders;

use App\Enums\ImpersonationPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ImpersonationPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $impersonation = [
            ImpersonationPermissionEnum::ImpersonateUser->value,
            ImpersonationPermissionEnum::StopImpersonation->value
        ];

        foreach ($impersonation as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
