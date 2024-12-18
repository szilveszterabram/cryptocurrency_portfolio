<?php

namespace App\Listeners;

use App\Events\AssetUpdated;
use App\Services\CoinFetchService;
use App\Services\PriceObservationService;
use Illuminate\Support\Facades\Log;

class MaybeNotify
{
    public function __construct(
        protected PriceObservationService $priceObservationService,
        protected CoinFetchService $coinFetchService,
    ) {}

    public function handle(AssetUpdated $event): void
    {
        $observations = $this->priceObservationService->getAllByAssetId($event->asset->asset_id);
        foreach ($observations as $observation) {
            if (!$observation['active']) {
                continue;
            }

            $asset = $this->coinFetchService->fetchAssetById($event->asset->asset_id);
            if ($asset['price_usd'] < $observation->target) {
                continue;
            }

            $observation->update([
                'active' => false,
            ]);
            Log::info('Target ' . $observation->target . ' reached on ' . $asset['name'] . ', now at: ' . $asset['price_usd']);
        }
    }
}
