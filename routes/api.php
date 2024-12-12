<?php

use App\Http\Controllers\CoinMarketController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // put the methods here once everything works
});


Route::get('/assets', [CoinMarketController::class, 'assets']);
Route::get('/assets_icons', [CoinMarketController::class, 'assets_icons']);
Route::get('/assets_page/{key}', [CoinMarketController::class, 'assets_page']);
Route::get('/assets_icons_id', [CoinMarketController::class, 'assets_icons_id']);
