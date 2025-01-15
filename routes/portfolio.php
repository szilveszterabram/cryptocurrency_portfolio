<?php

use App\Http\Controllers\PortfolioController;
use App\Models\Portfolio;

Route::prefix('portfolio')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])
        ->name('portfolio');

    Route::get('/create', [PortfolioController::class, 'create'])
        ->name('portfolio.create')
        ->can('create',  Portfolio::class);

    Route::post('/store', [PortfolioController::class, 'store'])
        ->name('portfolio.store')
        ->can('create', Portfolio::class);

    Route::get('/{portfolio}', [PortfolioController::class, 'show'])
        ->name('portfolio.show')
        ->can('view', 'portfolio');

    Route::get('/{portfolio}/edit', [PortfolioController::class, 'edit'])
        ->name('portfolio.edit')
        ->can('update', 'portfolio');

    Route::patch('/{portfolio}', [PortfolioController::class, 'update'])
        ->name('portfolio.update')
        ->can('update', 'portfolio');

    Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])
        ->name('portfolio.destroy')
        ->can('delete', 'portfolio');
});
