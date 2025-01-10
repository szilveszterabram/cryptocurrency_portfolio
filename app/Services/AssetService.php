<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Entry;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AssetService
{
    public function __construct(protected CoinFetchService $coinFetchService) {}

    public function getAll(string $searchParam = ""): LengthAwarePaginator
    {
        return Asset
            ::where([
                ['asset_id', 'LIKE', "%{$searchParam}%"],
                ['price_usd', '>', -1],
                ['icon_url', '!=', null]])
            ->paginate();
    }

    public function getAssetsForEntries(Entry|Collection $entries): Collection
    {
        $res = new Collection();

        $entries = $entries instanceof Entry ? [$entries] : $entries;

        foreach ($entries as $entry) {
            $asset = $this->getAssetData($entry['asset_short']);
            $res->push($asset);
        }

        return $res;
    }

    private function getAssetData(string $assetShort): array
    {
        $dbAsset = Asset::where('asset_id', $assetShort)->first();
        $asset = $this->coinFetchService->fetchAssetById($assetShort);
        $asset['icon_url'] = $dbAsset->icon_url;

        return $asset;
    }

    public function getAssetByAssetId(string $assetId): Asset
    {
        return Asset::where('asset_id', $assetId)->firstOrFail();
    }
}
