<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEntryRequest;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\EntryService;
use App\Services\PortfolioService;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EntryController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService,
        protected CoinFetchService $coinFetchService,
        protected EntryService $entryService,
        protected AssetService $assetService,
        protected ProfileService $profileService,
    ) {}

    public function create(Request $request): Response
    {
        $assetId = $request->assetId;

        if (!$this->portfolioService->userHasPortfolios()) {
            return $this->portfolioService->redirectToCreate($assetId);
        }

        return Inertia::render('Entry/Create', [
            'balance' => $this->profileService->getUserBalance(),
            'asset' => $this->coinFetchService->fetchAssetById($assetId),
            'icon_url' => $this->assetService->getAssetByAssetId($assetId)['icon_url'],
            'portfolios' => $this->portfolioService->getUserPortfolios(),
        ]);
    }

    public function store(CreateEntryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $portfolio = $this->portfolioService->getById($validated['portfolio_id']);
        $this->entryService->create($portfolio, $validated);
        $this->profileService->substractFromUserBalance($validated['amount'] * $validated['price_at_buy']);

        return redirect()->route('portfolio.show', $portfolio);
    }

    public function destroy(string $entry): RedirectResponse
    {
        return $this->entryService->destroy($entry);
    }
}
