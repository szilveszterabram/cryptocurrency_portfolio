<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => RoleEnum::ADMIN]);
        Role::create(['name' => RoleEnum::USER]);
        Role::create(['name' => RoleEnum::GUEST]);
    }
}
