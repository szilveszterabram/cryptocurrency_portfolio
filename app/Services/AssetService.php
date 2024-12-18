<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AssetService
{
    public function __construct(protected CoinFetchService $coinFetchService) {}

    public function getAll(): LengthAwarePaginator
    {
        return Asset
            ::where([
                ['price_usd', '>', -1],
                ['icon_url', '!=', null]])
            ->paginate();
    }

    public function getAssetsForEntries(Collection $entries): Collection
    {
        $res = new Collection();
        foreach ($entries as $entry) {
            $dbAsset = Asset::where('asset_id', $entry['asset_short'])->first();
            $asset = $this->coinFetchService->fetchAssetById($entry['asset_short']);
            $asset['icon_url'] = $dbAsset->icon_url;
            $res->push($asset);
        }
        return $res;
    }

    public function getAssetByAssetId(string $assetId): Asset
    {
        return Asset::where('asset_id', $assetId)->firstOrFail();
    }
}
