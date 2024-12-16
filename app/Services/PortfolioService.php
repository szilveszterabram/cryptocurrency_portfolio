<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioService
{
    protected Portfolio $portfolio;
    protected User $user;

    public function __construct(Portfolio $portfolio, User $user)
    {
        $this->user = $user;
        $this->portfolio = $portfolio;
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
        return $this->getUserPortfolios()->isEmpty();
    }

    public function redirectToCreate(string $assetId): Response
    {
        Session::put('redirect_to_entry_create', true);
        Session::put('asset_id_to_create', $assetId);

        return Inertia::render('Portfolio/Create');
    }
}
