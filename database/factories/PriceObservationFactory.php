<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceObservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'asset_id' => Asset::factory()->create()->asset_id,
            'target' => $this->faker->randomFloat(7),
            'active' => true
        ];
    }
}
