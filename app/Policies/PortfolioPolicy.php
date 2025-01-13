<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Portfolio $portfolio): bool
    {
        if (!$user->can('navigate to portfolio.show')) {
            return false;
        }

        return $user->id === $portfolio->user_id;
    }

    public function create(User $user): bool
    {
        return $user->can('navigate to portfolio.create') && $user->can('create portfolio at portfolio.store');
    }

    public function update(User $user, Portfolio $portfolio): bool
    {
        if (!$user->can('navigate to portfolio.edit')) {
            return false;
        }

        if (!$user->can('edit a portfolio at portfolio.update')) {
            return false;
        }

        return $user->id === $portfolio->user_id;
    }

    public function delete(User $user, Portfolio $portfolio): bool
    {
        if (!$user->can('delete a portfolio at portfolio.destroy')) {
            return false;
        }

        return $user->id === $portfolio->user_id;
    }
}
