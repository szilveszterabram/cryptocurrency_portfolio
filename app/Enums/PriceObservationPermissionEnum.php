<?php

namespace App\Enums;

enum PriceObservationPermissionEnum: string
{
    case NavigateToObservation = 'navigate to observation';
    case NavigateToCreate = 'navigate to observation.create';
    case CreateObservation = 'create observations at observation.store';
    case DeleteObservation = 'delete observations at observation.destroy';
}
