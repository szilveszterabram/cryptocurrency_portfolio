<?php

namespace App\Services;

use App\Enums\CacheKeys;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function getIconUrl(string $assetId): string
    {
        return Cache::get('assets:icon:' . $assetId);
    }

    public function getCacheKey(CacheKeys $key, ?string $value = null): string
    {
        return $key->value . $value;
    }

    public function getRequestPage(Request $request): int
    {
        $pageNumber = $request->key;
        if ($pageNumber === null) {
            return 1;
        }
        return $pageNumber;
    }
}
