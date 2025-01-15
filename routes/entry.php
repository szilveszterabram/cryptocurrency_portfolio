<?php

use App\Http\Controllers\EntryController;
use App\Models\Entry;

Route::prefix('entry')->group(function () {
    Route::get('/create', [EntryController::class, 'create'])
        ->name('entry.create')
        ->can('create', Entry::class);

    Route::post('/store', [EntryController::class, 'store'])
        ->name('entry.store')
        ->can('create', Entry::class);

    Route::delete('/{entry}', [EntryController::class, 'destroy'])
        ->name('entry.destroy')
        ->can('delete', 'entry');
});
