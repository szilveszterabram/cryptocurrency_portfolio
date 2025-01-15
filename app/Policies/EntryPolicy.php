<?php

namespace App\Policies;

use App\Enums\EntryPermissionEnum;
use App\Models\Entry;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        if (!$user->can(EntryPermissionEnum::NavigateToCreate->value)) {
            return false;
        }

        if (!$user->can(EntryPermissionEnum::CreateEntry->value)) {
            return false;
        }

        // Make sure a user doesn't buy coins for someone else.
        return auth()->user()->id === $user->id;
    }

    public function delete(User $user, Entry $entry): bool
    {
        if (!$user->can(EntryPermissionEnum::DeleteEntry->value)) {
            return false;
        }

        $portfolio = Portfolio::find($entry->portfolio_id);
        return $portfolio->user_id === $user->id;
    }
}
