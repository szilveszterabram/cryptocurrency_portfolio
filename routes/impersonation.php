<?php

use App\Enums\ImpersonationPermissionEnum;
use App\Http\Controllers\ImpersonationController;

Route::prefix('impersonate')->group(function () {
    Route::post('/start/{user}', [ImpersonationController::class, 'start'])
        ->name('impersonate.start')
        ->middleware('permission:' . ImpersonationPermissionEnum::ImpersonateUser->value);

    Route::post('/stop', [ImpersonationController::class, 'stop'])
        ->name('impersonate.stop')
        ->middleware('permission:' . ImpersonationPermissionEnum::StopImpersonation->value);
});
