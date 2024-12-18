<?php

namespace App\Providers;

use App\Events\LoggedIn;
use App\Listeners\FetchAssetData;
use App\Models\Asset;
use App\Observers\AssetObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
