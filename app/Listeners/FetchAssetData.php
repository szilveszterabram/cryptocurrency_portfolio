<?php

namespace App\Listeners;

use App\Events\AssetFetchSchedule;
use App\Events\LoggedIn;
use App\Http\Controllers\CoinMarketController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class FetchAssetData implements ShouldQueue
{
    use InteractsWithQueue;
    protected CoinMarketController $coinController_;
    /**
     * Create the event listener.
     */
    public function __construct(CoinMarketController $coinController_)
    {
        $this->coinController_ = $coinController_;
        Log::debug("FetchAssetData constructed");
    }

    /**
     * Handle the event.
     */
    public function handle(LoggedIn|AssetFetchSchedule $event)
    {
        $this->coinController_->assets();
        $this->coinController_->assets_icons();
    }
}
