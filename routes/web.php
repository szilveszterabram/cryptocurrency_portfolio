<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/login', 'Auth/Login');
Route::inertia('/confirm-password', 'Auth/ConfirmPassword');
Route::inertia('/reset-password', 'Auth/ResetPassword');
Route::inertia('/forgot-password', 'Auth/ForgotPassword');
Route::inertia('/verify-email', 'Auth/VerifyEmail');

Route::get('/register', [RegisteredUserController::class, 'show'])
    ->name('register');

Route::inertia('/', 'Welcome')
    ->name('welcome');

Route::get('/assets', [AssetController::class, 'index'])
    ->name('assets');

Route::middleware(['auth'])->group(function () {
    require __DIR__.'/admin.php';
    require __DIR__.'/entry.php';
    require __DIR__.'/invitation.php';
    require __DIR__.'/portfolio.php';
    require __DIR__.'/price_observation.php';
    require __DIR__.'/profile.php';
});

require __DIR__.'/auth.php';
