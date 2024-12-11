<?php

use App\Http\Controllers\CoinMarketController;

Route::get('/assets', [CoinMarketController::class, 'assets']);
Route::get('/assets_page/{key}', [CoinMarketController::class, 'assets_page']);
