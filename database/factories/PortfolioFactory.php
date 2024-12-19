<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortfolioFactory extends Factory
{
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            'user_id' => $user->id,
            'name' => $this->faker->name(),
        ];
    }
}
