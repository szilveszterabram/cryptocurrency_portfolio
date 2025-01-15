<?php

namespace App\Policies;

use App\Enums\PortfolioPermissionEnum;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Portfolio $portfolio): bool
    {
        if (!$user->can(PortfolioPermissionEnum::NavigateToShow->value)) {
            return false;
        }

        return $user->id === $portfolio->user_id;
    }

    public function create(User $user): bool
    {
        return $user->can(PortfolioPermissionEnum::NavigateToCreate->value) && $user->can(PortfolioPermissionEnum::CreatePortfolio->value);
    }

    public function update(User $user, Portfolio $portfolio): bool
    {
        if (!$user->can(PortfolioPermissionEnum::NavigateToEdit->value)) {
            return false;
        }

        if (!$user->can(PortfolioPermissionEnum::UpdatePortfolio->value)) {
            return false;
        }

        return $user->id === $portfolio->user_id;
    }

    public function delete(User $user, Portfolio $portfolio): bool
    {
        if (!$user->can(PortfolioPermissionEnum::DeletePortfolio->value)) {
            return false;
        }

        return $user->id === $portfolio->user_id;
    }
}
