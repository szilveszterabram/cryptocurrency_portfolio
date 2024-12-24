<?php

use App\Http\Controllers\EntryController;
use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;
use App\Models\User;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->user = User::factory()->create();
    login($this->user);

    $this->withoutMiddleware();
});

describe('EntryController', function() {
    test('create redirects to portfolio creation page if the user does not have any portfolios', function () {
        Asset::factory()->count(1)->create();

        $response = get(action([EntryController::class, 'create']));
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Portfolio/Create')
        );
    });

    test('create redirects to entry creation page if a user has at least one portfolio', function () {
        Portfolio::factory()->for($this->user)->count(1)->create();

        Asset::factory()->create([
            'asset_id' => 'BTC',
            'name' => 'Bitcoin',
        ]);

        $asset = Asset::first();

        $response = get(action([EntryController::class, 'create'], ['assetId' => $asset->asset_id]));
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Entry/Create')
        );
    });

    test('store validates the request data, creates the entry and redirects to the portfolio', function () {
        Portfolio::factory()->for($this->user)->count(1)->create();
        $portfolio = Portfolio::first();

        $asset = Asset::factory()->create([
            'asset_id' => 'BTC',
            'name' => 'Bitcoin',
        ]);

        $data = [
            'portfolio_id' => $portfolio->id,
            'asset_short' => $asset->asset_id,
            'asset_long' => $asset->name,
            'amount' => 1.5,
            'price_at_buy' => 50000,
        ];

        $response = post(action([EntryController::class, 'store'], [...$data]));
        $response->assertRedirect('portfolio');

        $entry = Entry::first();

        expect($entry)
            ->portfolio_id->toBe($portfolio->id)
            ->asset_short->toBe($asset->asset_id)
            ->asset_long->toBe($asset->name)
            ->amount->toEqual(1.5)
            ->price_at_buy->toEqual(50000.0);
    });

    test('destroy delete the given entry from the database', function () {
        Entry::factory()->create();
        $entry = Entry::first();

        delete(action([EntryController::class, 'destroy'], ['entry' => $entry->id]));
        $result = Entry::find($entry->id);

        expect($result)->toBeNull();
    });
});

