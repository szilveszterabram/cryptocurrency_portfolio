<?php

use App\Http\Controllers\PortfolioController;
use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;
use App\Models\User;
use App\Services\PortfolioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Inertia\Response;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function PHPUnit\Framework\assertEquals;

describe("PortfolioService", function() {
    test('hasEntryRedirectFlag check session storage for flag', function () {
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        session()->push('redirect_to_entry_create', true);
        $checkResult = $portfolioService->hasEntryRedirectFlag();

        expect($checkResult)->toBeTrue();

        session()->remove('redirect_to_entry_create');
        $checkResult = $portfolioService->hasEntryRedirectFlag();

        expect($checkResult)->toBeFalse();
    });

    test('redirectToEntryCreate redirects to entry.create with the correct asset id', function () {
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        $user = User::factory()->create();
        actingAs($user);

        $asset = Asset::factory()->create();
        Session::put('asset_id_to_create', $asset->asset_id);

        $response = $portfolioService->redirectToEntryCreate();

        expect($response)
            ->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getStatusCode())
            ->toBe(302)
        ->and($response->getTargetUrl())
            ->toBe(route('entry.create', [
                'asset_id' => $asset->asset_id
            ]));
    });

    test('getById returns a portfolio by id from the authenticated user', function () {
        $user = User::factory()->create();
        actingAs($user);
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        Portfolio::factory()->for($user)->count(1)->create();
        $createdPortfolio = Portfolio::first();
        $queriedPortfolio = $portfolioService->getById($createdPortfolio->id);

        assertEquals($createdPortfolio, $queriedPortfolio);
    });

    test('getUserPortfolios returns all portfolios for the authenticated user', function () {
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        $user = User::factory()->create();
        actingAs($user);

        Portfolio::factory()->for($user)->count(5)->create();
        $portfolios = $portfolioService->getUserPortfolios();

        expect($portfolios)
            ->toBeInstanceOf(Collection::class)
        ->and($portfolios->count())
            ->toBe(5);
    });

    test('userHasPortfolios return a boolean value that indicates if a user has any portfolios or not', function () {
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        $user = User::factory()->create();
        actingAs($user);

        $hasPortfolios = $portfolioService->userHasPortfolios();

        expect($hasPortfolios)->toBeFalse();

        Portfolio::factory()->for($user)->count(1)->create();

        $hasPortfolios = $portfolioService->userHasPortfolios();

        expect($hasPortfolios)->toBeTrue();
    });

    test('redirectToCreate stores necessary asset information in the session storage and redirects to portfolio.create', function () {
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        // Note:
        // If you don't disable the authentication middleware and try to make an Inertia action without acting as a User,
        // you will get redirected to the login screen, thus getting the 'Not a valid Inertia response error'.
        $this->withoutMiddleware();

        Asset::factory()->count(1)->create();
        $asset = Asset::first();

        $response = $portfolioService->redirectToCreate($asset->asset_id);

        expect(Session::has('redirect_to_entry_create'))
            ->toBeTrue()
        ->and(Session::get('asset_id_to_create'))
            ->toBe($asset->asset_id)
        ->and($response)
            ->toBeInstanceOf(Response::class);

        $response = get(action([PortfolioController::class, 'create']));
        $response->assertInertia(fn (AssertableInertia $page) =>
            $page
                ->component('Portfolio/Create')
        );
    });

    test('getEntries returns all entries corresponding to a given portfolio', function () {
        $portfolioService = new PortfolioService(
            new Portfolio(),
            new User()
        );

        Portfolio::factory()->count(1)->create();
        $portfolio = Portfolio::first();

        Entry::factory()->for($portfolio)->count(5)->create();

        $entries = $portfolioService->getEntries($portfolio);

        expect($entries)
            ->toBeInstanceOf(Collection::class)
        ->and($entries->count())
            ->toBe(5);
    });
});

