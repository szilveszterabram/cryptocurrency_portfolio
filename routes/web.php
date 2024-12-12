<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
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
});

Route::middleware('auth')->group(function () {
    Route::get('/assets-first', [AssetController::class, 'first_page'])->name('assets.first');
    Route::get('/assets/{key}', [AssetController::class, 'page'])->name('assets.page');
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio/store', [PortfolioController::class, 'store'])->name('portfolio.store');
});

require __DIR__.'/auth.php';
require __DIR__.'/api.php';
