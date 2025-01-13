<?php

namespace App\Policies;

use App\Models\PriceObservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PriceObservationPolicy
{
    use HandlesAuthorization;
    public function view(User $user): bool
    {
        return $user->can('navigate to observation');
    }

    public function create(User $user): bool
    {
        if (!$user->can('navigate to observation.create')) {
            return false;
        }

        if (!$user->can('create observations at observation.store')) {
            return false;
        }

        return true;
    }

    public function delete(User $user, PriceObservation $priceObservation): bool
    {
        if (!$user->can('delete observations at observation.destroy')) {
            return false;
        }

        return $priceObservation->user_id === $user->id;
    }
}
