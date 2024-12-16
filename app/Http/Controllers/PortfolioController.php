<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\CoinFetchService;
use App\Services\PortfolioService;
use App\Services\ValidationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService,
        protected ValidationService $validationService,
        protected CoinfetchService $coinFetchService,
    ) {}

    public function index() {
        $portfolios = $this->portfolioService->getUserPortfolios();

        return Inertia::render('Portfolio/Index', [
            'portfolios' => $portfolios,
        ]);
    }

    public function create() {
        return Inertia::render('Portfolio/Create');
    }

    public function store(Request $request) {
        $validated = $this->validationService->validatePortfolio($request);

        $this->portfolioService->create($validated);

        if ($this->portfolioService->hasEntryRedirectFlag()) {
            $this->portfolioService->redirectToEntryCreate();
        }

        return redirect(route('portfolio'));
    }

    public function show(Portfolio $portfolio) {
        $entries = $this->portfolioService->getEntries($portfolio);
        $entryIds = $entries->pluck('asset_short');

        $icons = $this->portfolioService->getEntryIcons($entryIds);
        $assets = $this->coinFetchService->fetchAssetsById($icons);

        return Inertia::render('Portfolio/Show', [
            'portfolio' => $portfolio,
            'entries' => $entries,
            'icons' => $icons,
            'data' => $assets,
        ]);
    }

    public function edit(Portfolio $portfolio) {
        return Inertia::render('Portfolio/Edit', [
            'portfolio' => $portfolio,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio) {
        $validated = $this->validationService->validatePortfolio($request);
        $this->portfolioService->update($portfolio, $validated);

        return redirect('/portfolio');
    }

    public function destroy($portfolio) {
        $this->portfolioService->destroy($portfolio);
    }
}
