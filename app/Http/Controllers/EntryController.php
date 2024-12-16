<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use App\Services\CoinFetchService;
use App\Services\EntryService;
use App\Services\PortfolioService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EntryController extends Controller
{
    protected PortfolioService $portfolioService;
    protected CacheService $cacheService;
    protected CoinFetchService $coinFetchService;
    protected ValidationService $validationService;
    protected EntryService $entryService;

    public function __construct(
        PortfolioService $portfolioService,
        CacheService $cacheService,
        CoinFetchService $coinFetchService,
        ValidationService $validationService,
        EntryService $entryService
    )
    {
        $this->portfolioService = $portfolioService;
        $this->cacheService = $cacheService;
        $this->coinFetchService = $coinFetchService;
        $this->validationService = $validationService;
        $this->entryService = $entryService;
    }

    public function create(Request $request): Response
    {
        $assetId = $request->assetId;

        if (!$this->portfolioService->userHasPortfolios()) {
            return $this->portfolioService->redirectToCreate($assetId);
        }

        $portfolios = $this->portfolioService->getUserPortfolios();
        $asset = $this->coinFetchService->fetchAssetById($assetId);
        $iconUrl = $this->cacheService->getIconUrl($assetId);


        return Inertia::render('Entry/Create', [
            'asset' => $asset,
            'icon_url' => $iconUrl,
            'portfolios' => $portfolios,
        ]);
    }

    public function store(Request $request) {
        $validated = $this->validationService->validateEntry($request);

        $portfolio = $this->portfolioService->getById($validated['portfolio_id']);
        $this->entryService->create($portfolio, $validated);

        return redirect('portfolio');
    }
}
