<?php

namespace App\Listeners;

use App\Events\AssetUpdated;
use App\Mail\PriceTargetReachedMail;
use App\Services\CoinFetchService;
use App\Services\PriceObservationService;
use App\Services\UserService;
use Illuminate\Support\Facades\Mail;

class MaybeNotify
{
    public function __construct(
        protected PriceObservationService $priceObservationService,
        protected CoinFetchService $coinFetchService,
        protected UserService $userService,
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

            $user = $this->userService->getById($observation['user_id']);
            Mail::to($user)->queue(new PriceTargetReachedMail($user, $observation, $asset));
        }
    }
}
