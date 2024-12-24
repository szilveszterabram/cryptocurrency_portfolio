<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\PortfolioService;
use App\Services\ValidationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService,
        protected ValidationService $validationService,
        protected CoinfetchService $coinFetchService,
        protected AssetService $assetService,
    ) {}

    public function index(): Response
    {
        $portfolios = $this->portfolioService->getUserPortfolios();

        return Inertia::render('Portfolio/Index', [
            'portfolios' => $portfolios,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Portfolio/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validationService->validatePortfolio($request);

        $this->portfolioService->create($validated);

        if ($this->portfolioService->hasEntryRedirectFlag()) {
            return $this->portfolioService->redirectToEntryCreate();
        }

        return redirect(route('portfolio'));
    }

    public function show(Portfolio $portfolio): Response
    {
        $entries = $this->portfolioService->getEntries($portfolio);
        $assets = $this->assetService->getAssetsForEntries($entries);

        return Inertia::render('Portfolio/Show', [
            'portfolio' => $portfolio,
            'entries' => $entries,
            'data' => $assets,
        ]);
    }

    public function edit(Portfolio $portfolio): Response
    {
        return Inertia::render('Portfolio/Edit', [
            'portfolio' => $portfolio,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $validated = $this->validationService->validatePortfolio($request);
        $this->portfolioService->update($portfolio, $validated);

        return redirect('/portfolio');
    }

    public function destroy($portfolio): void
    {
        $this->portfolioService->destroy($portfolio);
    }
}
