<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class AssetObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(protected User $user) {}

    public function created(Asset $asset): void
    {
        Log::debug('Running AssetObserver/created');
        $user = $this->user->getAuthenticatedUser();
        $observations = $user->priceObservations()->where('asset_id', $asset['asset_id'])->get();
        Log::debug('observations: ', $observations);
    }

    public function updated(Asset $asset): void
    {
        Log::debug('Running AssetObserver/updated');
        $user = $this->user->getAuthenticatedUser();
        $observations = $user->priceObservations()->where('asset_id', $asset['asset_id'])->get();
        Log::debug('observations: ', $observations);
    }

    public function deleted(Asset $asset): void {}

    public function restored(Asset $asset): void {}

    public function forceDeleted(Asset $asset): void {}
}
