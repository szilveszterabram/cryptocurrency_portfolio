<?php

use App\Http\Controllers\PriceObservationController;
use App\Models\Asset;
use App\Models\PriceObservation;
use App\Models\User;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withoutMiddleware;

beforeEach(function () {
    $this->user = User::factory()->create();
    login($this->user);

    withoutMiddleware();
});

describe("PriceObservationController", function() {
    test('index redirects to the price observation index page with all user observations', function() {
        PriceObservation::factory()
            ->for($this->user)
            ->count(3)
            ->create();

        $response = get(action([PriceObservationController::class, 'index']));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PriceObservation/Index')
            ->has('observations', fn (AssertableInertia $pageProps) => $pageProps
                ->each(fn (AssertableInertia $observation) => $observation
                    ->hasAll(['id', 'user_id', 'asset_id', 'target', 'active', 'created_at', 'updated_at', 'icon_url'])
                )
            )
        );
    });

    test('create redirects to the price observation create page with the given asset data', function() {
        $icon_url = fake()->imageUrl(30, 30);
        Asset::factory()->create([
             'asset_id' => 'BTC',
             'name' => 'Bitcoin',
             'price_usd' => 123.123,
             'type_is_crypto' => true,
             'icon_url' => $icon_url,
        ]);

        $response = get(action([PriceObservationController::class, 'create'], [
            'asset' => 'BTC'
        ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PriceObservation/Create')
            ->has('asset', fn (AssertableInertia $assetProps) => $assetProps
                ->hasAll([
                    'asset_id',
                    'name',
                    'type_is_crypto',
                    'type_is_crypto',
                    'data_quote_start',
                    'data_quote_end',
                    'data_orderbook_start',
                    'data_orderbook_end',
                    'data_trade_start',
                    'data_trade_end',
                    'data_symbols_count',
                    'volume_1hrs_usd',
                    'volume_1day_usd',
                    'volume_1mth_usd',
                    'price_usd',
                    'id_icon',
                    'chain_addresses',
                    'data_start',
                    'data_end',
                ])
                ->where('asset_id', 'BTC')
                ->where('name', 'Bitcoin')
                ->where('type_is_crypto', 1)
            )
            ->where('icon_url', $icon_url)
        );
    });

    test('store validates the request data, creates a price observation and redirects to observations index page', function() {
        $before = PriceObservation::all();
        expect($before)->toBeInstanceOf(Collection::class)->toBeEmpty();

        $data = [
            'target' => 123000.321,
            'active' => true,
            'asset_id' => 'BTC',
        ];

        $response = post(action([PriceObservationController::class, 'store'], $data));

        $after = PriceObservation::all();
        expect($after)
            ->toBeInstanceOf(Collection::class)
            ->not
            ->toBeEmpty()
        ->first()->asset_id
            ->toBe('BTC')
        ->and($response)
            ->assertStatus(302)
            ->assertRedirectToRoute('observation');
    });

    test('destroy deletes the given observation without redirect', function() {
         $observation = PriceObservation::factory()->for($this->user)->create();

         delete(action([PriceObservationController::class, 'destroy'], [ 'observation' => $observation ]));

         $all = PriceObservation::all();
         expect($all->count())->toBe(0);
    });
});

