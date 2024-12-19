<?php

namespace Database\Seeders;

use App\Models\Entry;
use Illuminate\Database\Seeder;

class  EntryTestSeeder extends Seeder
{
    public function run(): void
    {
        Entry::factory()
            ->count(250)
            ->create();
    }
}
