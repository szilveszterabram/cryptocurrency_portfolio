<?php

use App\Enums\ProfilePermissionEnum;
use App\Http\Controllers\ProfileController;

Route::prefix('profile')->middleware('permission:navigate to profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])
        ->name('profile');

    Route::put('/add-balance', [ProfileController::class, 'updateBalance'])
        ->name('profile.update-balance')
        ->middleware('permission:' . ProfilePermissionEnum::AddToBalance->value);
});
