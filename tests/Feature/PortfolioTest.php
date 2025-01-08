<?php

use App\Models\Portfolio;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    login($this->user);

    $this->portfolio = new Portfolio();
});

describe("Portfolio", function() {
    test("create creates a portfolio for the authenticated user", function() {
        $data = [
            'name' => fake()->word(),
        ];

        $this->portfolio->create($this->user, $data);

        expect(Portfolio::first())
            ->tobeinstanceof(Portfolio::class)
        ->user_id->toBe($this->user->id);
    });
});
