<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePortfolioRequest;
use App\Models\Portfolio;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use App\Services\PortfolioService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService,
        protected CoinfetchService $coinFetchService,
        protected AssetService $assetService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Portfolio/Index', [
            'portfolios' => $this->portfolioService->getUserPortfolios(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Portfolio/Create');
    }

    public function store(CreatePortfolioRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->portfolioService->create($validated);

        if ($this->portfolioService->hasEntryRedirectFlag()) {
            return $this->portfolioService->redirectToEntryCreate();
        }

        return redirect()->route('portfolio')->with('success', 'Your portfolio has been created successfully.');
    }

    public function show(Portfolio $portfolio): Response
    {
        $entries = $this->portfolioService->getEntries($portfolio);

        return Inertia::render('Portfolio/Show', [
            'portfolio' => $portfolio,
            'entries' => $entries,
            'data' => $this->assetService->getAssetsForEntries($entries),
        ]);
    }

    public function edit(Portfolio $portfolio): Response
    {
        return Inertia::render('Portfolio/Edit', [
            'portfolio' => $portfolio,
        ]);
    }

    public function update(CreatePortfolioRequest $request, Portfolio $portfolio): RedirectResponse
    {
        $validated = $request->validated();
        $this->portfolioService->update($portfolio, $validated);

        return redirect()->route('portfolio')->with('success', 'Your portfolio has been updated successfully.');
    }

    public function destroy($portfolio): void
    {
        $this->portfolioService->destroy($portfolio);
    }
}
