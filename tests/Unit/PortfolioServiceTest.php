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
use function Pest\Laravel\get;
use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    $this->portfolioService = new PortfolioService(
        new Portfolio(),
        new User()
    );

    $this->user = User::factory()->create();
    login($this->user);
});

describe("PortfolioService", function() {
    test('hasEntryRedirectFlag check session storage for flag', function () {
        session()->push('redirect_to_entry_create', true);
        $checkResult = $this->portfolioService->hasEntryRedirectFlag();

        expect($checkResult)->toBeTrue();

        session()->remove('redirect_to_entry_create');
        $checkResult = $this->portfolioService->hasEntryRedirectFlag();

        expect($checkResult)->toBeFalse();
    });

    test('redirectToEntryCreate redirects to entry.create with the correct asset id', function () {
        $asset = Asset::factory()->create();
        Session::put('asset_id_to_create', $asset->asset_id);

        $response = $this->portfolioService->redirectToEntryCreate();

        expect($response)
            ->toBeInstanceOf(RedirectResponse::class)
        ->getStatusCode()
            ->toBe(302)
        ->getTargetUrl()
            ->toBe(route('entry.create', [
                'asset_id' => $asset->asset_id
            ]));
    });

    test('getById returns a portfolio by id from the authenticated user', function () {
        Portfolio::factory()->for($this->user)->count(1)->create();
        $createdPortfolio = Portfolio::first();
        $queriedPortfolio = $this->portfolioService->getById($createdPortfolio->id);

        assertEquals($createdPortfolio, $queriedPortfolio);
    });

    test('getUserPortfolios returns all portfolios for the authenticated user', function () {
        Portfolio::factory()->for($this->user)->count(5)->create();
        $portfolios = $this->portfolioService->getUserPortfolios();

        expect($portfolios)
            ->toBeInstanceOf(Collection::class)
        ->count()
            ->toBe(5);
    });

    test('userHasPortfolios return a boolean value that indicates if a user has any portfolios or not', function () {
        $hasPortfolios = $this->portfolioService->userHasPortfolios();

        expect($hasPortfolios)->toBeFalse();

        Portfolio::factory()->for($this->user)->count(1)->create();

        $hasPortfolios = $this->portfolioService->userHasPortfolios();

        expect($hasPortfolios)->toBeTrue();
    });

    test('redirectToCreate stores necessary asset information in the session storage and redirects to portfolio.create', function () {
        Asset::factory()->count(1)->create();
        $asset = Asset::first();

        $response = $this->portfolioService->redirectToCreate($asset->asset_id);

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

        Portfolio::factory()->count(1)->create();
        $portfolio = Portfolio::first();

        Entry::factory()->for($portfolio)->count(5)->create();

        $entries = $this->portfolioService->getEntries($portfolio);

        expect($entries)
            ->toBeInstanceOf(Collection::class)
        ->count()
            ->toBe(5);
    });
});

