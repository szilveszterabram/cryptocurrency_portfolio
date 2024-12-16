<?php

namespace App\Services;

use App\Enums\CacheKeys;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioService
{
    protected Portfolio $portfolio;
    protected User $user;
    protected CacheService $cacheService;

    public function __construct(Portfolio $portfolio, User $user, CacheService $cacheService)
    {
        $this->user = $user;
        $this->portfolio = $portfolio;
        $this->cacheService = $cacheService;
    }

    public function hasEntryRedirectFlag(): bool
    {
        return session('redirect_to_entry_create') == null;
    }

    public function redirectToEntryCreate(): RedirectResponse
    {
        session()->forget('redirect_to_entry_create');
        $assetId = Session::get('asset_id_to_create');

        return redirect()->route('entry.create', [
                'asset_id' => $assetId
            ]
        );
    }

    public function create(array $data): Portfolio
    {
        return $this->portfolio->create($this->user->getAuthenticatedUser(), $data);
    }

    public function update(Portfolio $portfolio, array $data): Portfolio
    {
        $portfolio->update($data);
        return $portfolio;
    }

    public function getById(int $portfolioId): Portfolio
    {
        $user = $this->user->getAuthenticatedUser();
        return $user->portfolios()->findOrFail($portfolioId);
    }

    public function getUserPortfolios(): Collection
    {
        $user = $this->user->getAuthenticatedUser();
        return $user->portfolios()->get();
    }

    public function userHasPortfolios(): bool
    {
        return !($this->getUserPortfolios()->isEmpty());
    }

    public function redirectToCreate(string|null $assetId): Response
    {
        Session::put('redirect_to_entry_create', true);
        Session::put('asset_id_to_create', $assetId);
        Log::debug('Redirecting to portfolios/ create');
        return Inertia::render('Portfolio/Create');
    }

    public function getEntries(Portfolio $portfolio): Collection
    {
        return $portfolio->entries()->get();
    }

    public function getEntryIcons(Collection $entryIds): Collection
    {
        $result = collect();
        foreach ($entryIds as $entryId) {
            $key = $this->cacheService->getCacheKey(CacheKeys::ASSET_ICON, $entryId);
            $url = Cache::get($key);
            Log::debug($url);
            $result->push([
                'id' => $entryId,
                'url' => $url,
            ]);
        }

        return $result;
    }

    public function destroy(string $portfolio): void
    {
        $user = $this->user->getAuthenticatedUser();
        $portfolio = $user->portfolios()->findOrFail($portfolio);
        $portfolio->delete();
    }
}
