<?php

namespace App\Jobs;

use App\Services\CoinFetchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchAssetByIdJob implements ShouldQueue
{
    use Queueable;
    protected string $assetId;

    public function __construct(
        protected CoinFetchService $coinFetchService,
        string $assetId
    ) {
        $this->assetId = $assetId;
    }

    public function handle(): void
    {
        $this->coinFetchService->fetchAssetById($this->assetId);
    }
}
