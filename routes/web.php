<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PriceObservationController;
use App\Http\Controllers\ProfileController;
use App\Models\Entry;
use App\Models\Portfolio;
use App\Models\PriceObservation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

Route::inertia('/login', 'Auth/Login');
Route::inertia('/confirm-password', 'Auth/ConfirmPassword');
Route::inertia('/reset-password', 'Auth/ResetPassword');
Route::inertia('/forgot-password', 'Auth/ForgotPassword');
Route::inertia('/verify-email', 'Auth/VerifyEmail');

Route::get('/register', [RegisteredUserController::class, 'show'])->name('register');

Route::inertia('/', 'Welcome')->name('welcome');
Route::get('/assets', [AssetController::class, 'index'])->name('assets');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/{user}', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin/{user}/make-admin', [AdminController::class, 'makeAdmin'])->name('admin.make-admin');
    Route::patch('/admin/{user}/update', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/{user}/delete', [AdminController::class, 'destroy'])->name('admin.delete');

    Route::get('/invite', [InvitationController::class, 'index'])->name('invite');
    Route::post('/invite', [InvitationController::class, 'invite'])->name('invite.invite');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile/add-balance', [ProfileController::class, 'updateBalance'])->name('profile.update-balance');

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create')->can('create', Portfolio::class);
    Route::post('/portfolio/store', [PortfolioController::class, 'store'])->name('portfolio.store')->can('create', Portfolio::class);
    Route::get('/portfolio/{portfolio}', [PortfolioController::class, 'show'])->name('portfolio.show')->can('view', 'portfolio');
    Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit')->can('update', 'portfolio');
    Route::patch('/portfolio/{portfolio}', [PortfolioController::class, 'update'])->name('portfolio.update')->can('update', 'portfolio');
    Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy')->can('delete', 'portfolio');

    Route::get('/entry/create', [EntryController::class, 'create'])->name('entry.create')->can('create', Entry::class);
    Route::post('/entry/store', [EntryController::class, 'store'])->name('entry.store')->can('create', Entry::class);
    Route::delete('/entry/{entry}', [EntryController::class, 'destroy'])->name('entry.destroy')->can('delete', 'entry');

    Route::get('/observations', [PriceObservationController::class, 'index'])->name('observation')->can('view', PriceObservation::class);
    Route::get('/observation/{asset}', [PriceObservationController::class, 'create'])->name('observation.create')->can('create', PriceObservation::class);
    Route::post('/observation', [PriceObservationController::class, 'store'])->name('observation.store')->can('create', PriceObservation::class);
    Route::delete('/observation/{priceObservation}', [PriceObservationController::class, 'destroy'])->name('observation.destroy')->can('delete', 'priceObservation');
});

require __DIR__.'/auth.php';
