<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InvitePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $invite = [
            'navigate to invite',
            'send an invite at invite.invite'
        ];

        foreach ($invite as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
