<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\EntryService;
use App\Services\PortfolioService;
use App\Services\ProfileService;
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
        protected ProfileService $profileService,
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
        $balance = $this->profileService->getUserBalance();

        return Inertia::render('Entry/Create', [
            'balance' => $balance,
            'asset' => $asset,
            'icon_url' => $dbAsset['icon_url'],
            'portfolios' => $portfolios,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validationService->validateEntry($request);
        if (!$this->validationService->hasEnoughFundsToBuy($validated['amount'] * $validated['price_at_buy'])) {
            return back()->withErrors([
                'You do not have enough funds to buy this asset. Please lower the amount or add more funds to your account.',
            ]);
        }

        $portfolio = $this->portfolioService->getById($validated['portfolio_id']);
        $this->entryService->create($portfolio, $validated);
        $this->profileService->substractFromUserBalance($validated['amount'] * $validated['price_at_buy']);

        return redirect('portfolio');
    }

    public function destroy(string $entry): void
    {
        $this->entryService->destroy($entry);
    }
}
