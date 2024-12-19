<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetTestSeeder extends Seeder
{
    public function run(): void
    {
        Asset::factory()
            ->count(250)
            ->create();
    }
}
