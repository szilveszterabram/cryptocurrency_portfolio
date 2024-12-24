<?php

use App\Http\Controllers\PortfolioController;
use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withoutMiddleware;

beforeEach(function () {
   $this->user = User::factory()->create();
   login($this->user);

    Portfolio::factory()
        ->for($this->user)
        ->count(3)
        ->create();

    withoutMiddleware();
});

describe('PortfolioController', function() {
    test('index redirects to the index page with all user portfolios', function () {
        $response = get(action([PortfolioController::class, 'index']));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Portfolio/Index')
            ->has('portfolios', 3)
            ->has('portfolios.0', fn (AssertableInertia $page) => $page
                ->where('user_id', $this->user->id)
                ->hasAll(['id', 'user_id', 'name', 'created_at', 'updated_at'])
            )
        );
    });

    test('create renders the portfolios create page', function () {
        $response = get(action([PortfolioController::class, 'create']));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Portfolio/Create')
        );
    });

    test('store validates the request data, creates the portfolio and redirects to portfolio index without entry create flag', function (array $data) {
        $response = post(action([PortfolioController::class, 'store'], $data));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('portfolio'));
    })->with('create data');

    test('store validates the request data, creates the portfolio and redirects to entry create with entry create flag', function (array $data) {
        Session::put('redirect_to_entry_create', true);
        Session::put('asset_id_to_create', 'ENJ');

        $response = post(action([PortfolioController::class, 'store'], $data));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('entry.create', ['asset_id' => 'ENJ']));
    })->with('create data');

    test('show redirects to the portfolio show page with all entries inside it', function () {
        $portfolio = Portfolio::factory()->count(1)->create();
        Asset::factory()->create([
            [
                'asset_id' => 'BTC',
                'name' => 'Bitcoin',
                'price_usd' => 100000.54321,
                'type_is_crypto' => true,
                'icon_url' => fake()->imageUrl()
            ],
            [
                'asset_id' => 'ETH',
                'name' => 'Ethereum',
                'price_usd' => 352.34,
                'type_is_crypto' => true,
                'icon_url' => fake()->imageUrl()
            ]
        ]);

        Entry::factory()->for($portfolio)->create([
            [
                'asset_short' => 'BTC',
                'asset_long' => 'Bitcoin',
                'amount' => 0.003,
                'price_at_buy' => 63.123
            ],
            [
                'asset_short' => 'ETH',
                'asset_long' => 'Ethereum',
                'amount' => 15,
                'price_at_buy' => 132.22
            ]
        ]);
    });
});
