<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\EntryService;
use App\Services\PortfolioService;
use App\Services\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EntryController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService,
        protected CoinFetchService $coinFetchService,
        protected ValidationService $validationService,
        protected EntryService $entryService,
        protected AssetService $assetService,
    ) {}

    public function create(Request $request): Response
    {
        $assetId = $request->assetId;

        if (!$this->portfolioService->userHasPortfolios()) {
            return $this->portfolioService->redirectToCreate($assetId);
        }

        $portfolios = $this->portfolioService->getUserPortfolios();
        $asset = $this->coinFetchService->fetchAssetById($assetId);
        $dbAsset = $this->assetService->getAssetByAssetId($assetId);

        return Inertia::render('Entry/Create', [
            'asset' => $asset,
            'icon_url' => $dbAsset['icon_url'],
            'portfolios' => $portfolios,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validationService->validateEntry($request);

        $portfolio = $this->portfolioService->getById($validated['portfolio_id']);
        $this->entryService->create($portfolio, $validated);

        return redirect('portfolio');
    }

    public function destroy(string $entry): void
    {
        $this->entryService->destroy($entry);
    }
}
