<?php

namespace App\Services;

use App\Enums\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AssetService
{
    public function __construct(protected CacheService $cacheService) {}

    public function getIconUrlsForAssets(array $assets): array
    {
        $ids = [];
        foreach ($assets as $asset) {
            $ids[] = $asset['asset_id'];
        }
        $result = [];
        foreach ($ids as $id) {
            $result[] = [
                'asset_id' => $id,
                'url' => Cache::get('assets:icon:' . $id),
            ];
        }
        return $result;
    }

    public function getAssetPage(int $pageNumber): array
    {
        $key = $this->cacheService->getCacheKey(CacheKeys::AssetPage, $pageNumber);
        return Cache::get($key);
    }

    public function getAssetsTotalPages(): int
    {
        $key = $this->cacheService->getCacheKey(CacheKeys::AssetTotalPages, null);
        return Cache::get($key);
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
