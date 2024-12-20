<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\PriceObservationController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\PortfolioController;

Route::inertia('/register', 'Auth/Register');
Route::inertia('/login', 'Auth/Login');
Route::inertia('/confirm-password', 'Auth/ConfirmPassword');
Route::inertia('/reset-password', 'Auth/ResetPassword');
Route::inertia('/forgot-password', 'Auth/ForgotPassword');
Route::inertia('/verify-email', 'Auth/VerifyEmail');

Route::middleware('auth')->group(function () {
    Route::inertia('/', 'Welcome')
        ->name('welcome');
    Route::inertia('/profile', 'Profile/Edit')
        ->name('profile');
    Route::inertia('/assets', 'Asset/Index')
        ->name('assets');
});

Route::middleware('auth')->group(function () {
    Route::get('/assets', [AssetController::class, 'index'])->name('assets');

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio/store', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolio}', [PortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
    Route::patch('/portfolio/{portfolio}', [PortfolioController::class, 'update'])->name('portfolio.update');

    Route::get('/entry/create', [EntryController::class, 'create'])->name('entry.create');
    Route::post('/entry/store', [EntryController::class, 'store'])->name('entry.store');
    Route::delete('/entry/{entry}', [EntryController::class, 'destroy'])->name('entry.destroy');

    Route::get('/observations', [PriceObservationController::class, 'index'])->name('observation');
    Route::get('/observation/{asset}', [PriceObservationController::class, 'create'])->name('observation.create');
    Route::post('/observation', [PriceObservationController::class, 'store'])->name('observation.store');
    Route::delete('/observation/{observation}', [PriceObservationController::class, 'destroy'])->name('observation.destroy');
});

require __DIR__.'/auth.php';
