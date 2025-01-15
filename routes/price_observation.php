<?php

use App\Http\Controllers\PriceObservationController;
use App\Models\PriceObservation;

Route::prefix('observations')->group(function () {
    Route::get('/', [PriceObservationController::class, 'index'])
        ->name('observation')
        ->can('view', PriceObservation::class);

    Route::get('/{asset}', [PriceObservationController::class, 'create'])
        ->name('observation.create')
        ->can('create', PriceObservation::class);

    Route::post('/', [PriceObservationController::class, 'store'])
        ->name('observation.store')
        ->can('create', PriceObservation::class);

    Route::delete('/{priceObservation}', [PriceObservationController::class, 'destroy'])
        ->name('observation.destroy')
        ->can('delete', 'priceObservation');
});
