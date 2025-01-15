<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = [
            'navigate to admin',
            'view a user at admin.show',
            'make a user admin at admin.make-admin',
            'update user info at admin.update',
            'delete a user at admin.delete',
        ];

        foreach ($admin as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
