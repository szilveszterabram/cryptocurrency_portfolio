<?php

namespace App\Listeners;

use App\Events\AssetFetchSchedule;
use App\Events\LoggedIn;
use App\Http\Controllers\CoinMarketController;
use App\Jobs\FetchAssetIconsJob;
use App\Jobs\FetchAssetsJob;
use App\Services\CoinFetchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class FetchAssetData
{
    public function __construct(public CoinFetchService $coinFetchService) {}

    public function handle(LoggedIn $event)
    {
        FetchAssetsJob::dispatch($this->coinFetchService);
        FetchAssetIconsJob::dispatch($this->coinFetchService);
    }
}
