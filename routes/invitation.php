<?php

use App\Enums\InvitationPermissionEnum;
use App\Http\Controllers\InvitationController;

Route::prefix('invite')->middleware('permission:' . InvitationPermissionEnum::NavigateToInvite->value)->group(function () {
    Route::get('/', [InvitationController::class, 'index'])
        ->name('invite');

    Route::post('/', [InvitationController::class, 'invite'])
        ->name('invite.invite')
        ->middleware('permission:' . InvitationPermissionEnum::SendInvite->value);
});
