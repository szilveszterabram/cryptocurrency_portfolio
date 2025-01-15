<?php

namespace App\Providers;

use App\Events\LoggedIn;
use App\Listeners\FetchAssetData;
use App\Models\Asset;
use App\Observers\AssetObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'authentication' => function() {
                $user = auth()->user();
                return [
                    'user' => $user?->only(['id', 'name', 'email']),
                    'isAdmin' => $user ? $user->hasRole('admin') : false,
                ];
            },
            'impersonation' => function() {
                $originalUserId = session()->has('before_impersonation') ?
                    session()->get('before_impersonation') : null;
                $isImpersonating = (bool)$originalUserId;
                $impersonatedUser = $isImpersonating ? auth()->user() : null;
                return [
                    'isImpersonating' => $isImpersonating,
                    'user' => $isImpersonating ? [
                        'id' => $impersonatedUser->id,
                        'name' => $impersonatedUser->name,
                    ] : null,
                ];
            }
        ]);
    }
}
