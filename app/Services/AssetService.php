<?php

namespace App\Services;

use App\Enums\CacheKeys;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class AssetService
{
    public function __construct(protected CacheService $cacheService) {}

    public function getAll(): LengthAwarePaginator
    {
        return Asset
            ::where([
                ['price_usd', '>', -1],
                ['icon_url', '!=', null]])
            ->paginate();
    }

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
        $key = $this->cacheService->getCacheKey(CacheKeys::AssetTotalPages);
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
