<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Going from up to down all permissions will be granted starting from an index.
        // When 'navigate to ...' or '... at ...' are mentioned, the route's name is mentioned with it.

        // Guest
        $guest = [
            'navigate to welcome',
            'navigate to profile',
            'navigate to assets'
        ];

        // User
        $user = [
            'add to balance at profile.update-balance',
            'navigate to portfolio.create',
            'create portfolio at portfolio.store',
            'navigate to portfolio.show',
            'navigate to portfolio.edit',
            'edit a portfolio at portfolio.update',
            'delete a portfolio at portfolio.destroy',
            'navigate to entry.create',
            'buy assets at entry.store',
            'delete assets at entry.destroy',
            'navigate to observation',
            'navigate to observation.create',
            'create observations at observation.store',
            'delete observations at observation.destroy'
        ];

        foreach ([...$guest, ...$user] as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
