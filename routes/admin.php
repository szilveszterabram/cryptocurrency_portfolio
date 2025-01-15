<?php

use App\Enums\AdminPermissionEnum;
use App\Http\Controllers\AdminController;

Route::prefix('admin')->middleware('permission:' . AdminPermissionEnum::Navigate->value)->group(function () {
    Route::get('/', [AdminController::class, 'index'])
        ->name('admin');

    Route::get('/{user}', [AdminController::class, 'show'])
        ->name('admin.show')
        ->middleware('permission:' . AdminPermissionEnum::ViewUser->value);

    Route::post('/{user}/make-admin', [AdminController::class, 'makeAdmin'])
        ->name('admin.make-admin')
        ->middleware('permission:' . AdminPermissionEnum::MakeUserAdmin->value);

    Route::patch('/{user}/update', [AdminController::class, 'update'])
        ->name('admin.update')
        ->middleware('permission:' . AdminPermissionEnum::UpdateUser->value);

    Route::delete('/{user}/delete', [AdminController::class, 'destroy'])
        ->name('admin.delete')
        ->middleware('permission:' . AdminPermissionEnum::DeleteUser->value);
});
