<?php

namespace App\Jobs;

use App\Services\CoinFetchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchAssetIconsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public CoinFetchService $coinFetchService) {}

    public function handle(): void
    {
        $icons = $this->coinFetchService->fetchAssetIcons();
        $this->coinFetchService->updateIcons($icons);
    }
}
