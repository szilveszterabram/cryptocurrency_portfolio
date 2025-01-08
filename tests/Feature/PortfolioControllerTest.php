<?php

use App\Http\Controllers\PortfolioController;
use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\withMiddleware;
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
        Portfolio::factory()->create();
        $portfolio = Portfolio::first();

        Asset::factory()->create([
            'asset_id' => 'BTC',
            'name' => 'Bitcoin',
            'price_usd' => 100000.54321,
            'type_is_crypto' => true,
            'icon_url' => fake()->imageUrl()
        ]);

        Entry::factory()->for($portfolio)->create([
            'asset_short' => 'BTC',
            'asset_long' => 'Bitcoin',
            'amount' => 0.003,
            'price_at_buy' => 63.123
        ]);

        $response = get(action([PortfolioController::class, 'show'], $portfolio));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Portfolio/Show')
            ->has('portfolio')
            ->has('entries')
            ->has('data')
        );
    });

    test('edit redirect to the portfolio edit page with the correct portfolio model', function () {
       Portfolio::factory()->create();
       $portfolio = Portfolio::first();

       $response = get(action([PortfolioController::class, 'edit'], $portfolio));

       $response->assertInertia(fn (AssertableInertia $page) => $page
           ->component('Portfolio/Edit')
           ->has('portfolio')
       );
    });

    test('update validates the request data, updates the portfolio and redirects to portfolio index page', function () {
        Portfolio::factory()->create();
        $portfolio = Portfolio::first();

        $data = [
            'name' => 'Holdings',
        ];

        $response = patch(action([PortfolioController::class, 'update'], $portfolio), $data);

        expect($response)
            ->assertStatus(302)
            ->assertRedirect(route('portfolio'));
    });

    test('destroy deletes the given portfolio without redirect', function () {
        Portfolio::factory()->create();
        $portfolio = Portfolio::first();

        delete(action([PortfolioController::class, 'destroy'], $portfolio));

        $remaining = Portfolio::find($portfolio->id);

        expect($remaining)
            ->toBeNull();
    });
});
