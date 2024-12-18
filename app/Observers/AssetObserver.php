<?php

namespace App\Observers;

use App\Events\AssetUpdated;
use App\Models\Asset;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class AssetObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct() {}

    public function updated(Asset $asset): void
    {
        event(new AssetUpdated($asset));
    }
}
