<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => RoleEnum::Admin]);
        Role::create(['name' => RoleEnum::User]);
        Role::create(['name' => RoleEnum::Guest]);
    }
}
