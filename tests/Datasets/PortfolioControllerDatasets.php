<?php

use App\Models\Asset;

dataset('create data', function () {
    return [
        [['name' => fake()->word()]]
    ];
});
