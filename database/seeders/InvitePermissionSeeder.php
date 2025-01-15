<?php

namespace Database\Seeders;

use App\Enums\InvitationPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InvitePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $invite = [
            InvitationPermissionEnum::NavigateToInvite->value,
            InvitationPermissionEnum::SendInvite->value
        ];

        foreach ($invite as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
