<?php

use App\Http\Controllers\ImpersonationController;

Route::prefix('impersonate')->group(function () {
    Route::post('/start/{user}', [ImpersonationController::class, 'start'])
        ->name('impersonate.start');

    Route::post('/stop', [ImpersonationController::class, 'stop'])
        ->name('impersonate.stop');
});
