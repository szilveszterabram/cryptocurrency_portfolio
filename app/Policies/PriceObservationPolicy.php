<?php

namespace App\Policies;

use App\Enums\PriceObservationPermissionEnum;
use App\Models\PriceObservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PriceObservationPolicy
{
    use HandlesAuthorization;
    public function view(User $user): bool
    {
        return $user->can(PriceObservationPermissionEnum::NavigateToObservation->value);
    }

    public function create(User $user): bool
    {
        if (!$user->can(PriceObservationPermissionEnum::NavigateToCreate->value)) {
            return false;
        }

        if (!$user->can(PriceObservationPermissionEnum::CreateObservation->value)) {
            return false;
        }

        return true;
    }

    public function delete(User $user, PriceObservation $priceObservation): bool
    {
        if (!$user->can(PriceObservationPermissionEnum::DeleteObservation->value)) {
            return false;
        }

        return $priceObservation->user_id === $user->id;
    }
}
