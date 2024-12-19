<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'asset_id' => Str::random(4),
            'name' => fake()->name(),
            'price_usd' => fake()->randomFloat(7, 0, 1000000),
            'type_is_crypto' => fake()->boolean(),
            'icon_url' => fake()->imageUrl(300, 300),
        ];
    }
}
