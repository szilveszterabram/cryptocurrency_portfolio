<?php

namespace App\Listeners;

use App\Events\LoggedIn;
use App\Jobs\FetchAssetIconsJob;
use App\Jobs\FetchAssetsJob;
use App\Services\CoinFetchService;

class FetchAssetData
{
    public function __construct(public CoinFetchService $coinFetchService) {}

    public function handle(LoggedIn $event)
    {
        FetchAssetsJob::dispatch($this->coinFetchService);
        FetchAssetIconsJob::dispatch($this->coinFetchService);
    }
}
