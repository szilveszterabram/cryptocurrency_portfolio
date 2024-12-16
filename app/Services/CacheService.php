<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function __construct()
    {
    }

    public function getIconUrl(string $assetId): string
    {
        return Cache::get('assets:icon:' . $assetId);
    }

    public function getAssetPage(int $pageNumber): Array
    {

    }
}
