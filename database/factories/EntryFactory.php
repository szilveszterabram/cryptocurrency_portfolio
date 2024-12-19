<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    public function definition(): array
    {
        $asset = Asset::factory()->create();
        $portfolio = Portfolio::factory()->create();
        return [
            'portfolio_id' => $portfolio->id,
            'asset_short' => $asset->asset_id,
            'asset_long' => $asset->name,
            'amount' => $this->faker->numberBetween(1, 100),
            'price_at_buy' => $this->faker->randomFloat(7)
        ];
    }
}
