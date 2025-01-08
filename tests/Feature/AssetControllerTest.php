<?php

use App\Http\Controllers\AssetController;
use Database\Seeders\AssetTestSeeder;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

describe('AssetController', function() {
    test('index redirects to the assets index page with all assets in a paginated form', function () {
        login();
        seed(AssetTestSeeder::class);

        $response = get(action([AssetController::class, 'index']));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Asset/Index')
            ->has('assets', fn (AssertableInertia $page) => $page
                ->has('data')
                ->has('links')
                ->has('current_page')
                ->has('first_page_url')
                ->has('last_page')
                ->has('last_page_url')
                ->has('next_page_url')
                ->has('path')
                ->has('per_page')
                ->has('prev_page_url')
                ->has('to')
                ->has('from')
                ->has('total')
            )
        );
    });
});

