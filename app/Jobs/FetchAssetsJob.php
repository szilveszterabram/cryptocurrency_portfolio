<?php

namespace App\Jobs;

use App\Services\CoinFetchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchAssetsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public CoinFetchService $coinFetchService) {}

    public function handle(): void
    {
        $assets = $this->coinFetchService->fetchAssets();
        $this->coinFetchService->update($assets);
    }
}
