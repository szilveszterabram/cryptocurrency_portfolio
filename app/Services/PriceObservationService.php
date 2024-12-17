<?php

namespace App\Services;

use App\Models\PriceObservation;
use App\Models\User;

class PriceObservationService
{
    public function __construct(
        protected PriceObservation $priceObservation,
        protected User $user,
    ) {}

    public function create(array $data): bool
    {
        $user = $this->user->getAuthenticatedUser();
        $user->priceObservations()->create($data);
        return true;
    }
}
