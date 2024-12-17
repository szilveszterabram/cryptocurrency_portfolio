<?php

namespace App\Services;

use App\Enums\CacheKeys;
use App\Enums\CoinApiEndpoint;
use App\Models\Asset;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinFetchService
{
    public function __construct(protected CacheService $cacheService) {}

    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'X-CoinAPI-key' => config('app.coin_api_key'),
        ];
    }

    public function getBaseUrl(): string
    {
        return config('services.api.base_url');
    }

    public function getRoute(CoinApiEndpoint $coinApiEndpoint): string
    {
        return match ($coinApiEndpoint) {
            CoinApiEndpoint::Assets => config('services.api.assets'),
            CoinApiEndpoint::Icons => $this->getRoute(CoinApiEndpoint::Assets) . config('services.api.assets_icons') . '/30',
        };
    }

    public function getFullUrl(CoinApiEndpoint $coinApiEndpoint, array $pathParams = []): string
    {
        $url =  $this->getBaseUrl() . $this->getRoute($coinApiEndpoint);

        if (!empty($pathParams)) {
            $url .= '/' . implode(',', array_map('urlencode', $pathParams));
        }

        return $url;
    }

    public function fetchData(CoinApiEndpoint $coinApiEndpoint, array $params = [], array $pathParams = []): array
    {
        $headers = $this->getHeaders();
        $url = $this->getFullUrl($coinApiEndpoint, $pathParams);

        try {
            $response = Http::withHeaders($headers)->get($url, $params);
            return $response->json();
        } catch (ConnectionException $connectionException) {
            Log::error('Coin data fetch failed with error: ' .$connectionException->getMessage());
            return [];
        }
    }

    public function fetchAssetById(string $assetId): array
    {
        // get the 0th index of the result array as it contains the single fetched asset
        return $this->fetchData(CoinApiEndpoint::Assets, [], [$assetId])[0];
    }

    public function fetchAssetsById(Collection $icons): array
    {
        $assetIds = $icons->pluck('id');

        return $this->fetchData(CoinApiEndpoint::Assets, [], [...$assetIds]);
    }

    public function fetchAssets(): array
    {
        return $this->fetchData(CoinApiEndpoint::Assets);
    }

    public function fetchAssetIcons(): array
    {
        return $this->fetchData(CoinApiEndpoint::Icons);
    }

    public function update(array $data): void
    {
        foreach ($data as $asset) {
            Asset::updateOrCreate(
                [
                    'asset_id' => $asset['asset_id'],
                ],
                [
                    'asset_id' => $asset['asset_id'],
                    'name' => $asset['name'],
                    'price_usd' => array_key_exists('price_usd', $asset) ? $asset['price_usd'] : -1,
                    'type_is_crypto' => $asset['type_is_crypto'],
                ]
            );
        }
    }

    public function updateIcons(array $data): void
    {
        foreach ($data as $icon) {
            $asset = Asset::where('asset_id', $icon['asset_id'])->first();
            if ($asset) {
                $asset->update(
                    [
                        'icon_url' => $icon['url'],
                    ],
                );
            }
        }
    }

    public function storeAssetIconsInCache(array $icons): void
    {
        foreach($icons as $icon) {
            $key = $this->cacheService->getCacheKey(CacheKeys::AssetIcon, $icon['asset_id']);
            Cache::set($key, $icon['url']);
        }
        $totalKey = $this->cacheService->getCacheKey(CacheKeys::AssetTotalIcons);
        Cache::set($totalKey, count($icons));
    }

    public function storeAssetsInCache(array $assets, int $perPage = 50): void
    {
        $chunks = array_chunk($assets, $perPage);
        foreach ($chunks as $pageIndex => $chunk) {
            $key = $this->cacheService->getCacheKey(CacheKeys::AssetPage, $pageIndex + 1);
            Cache::set($key, $chunk);
        }

        $totalKey = $this->cacheService->getCacheKey(CacheKeys::AssetTotalPages);
        Cache::set($totalKey, count($chunks));
    }
}
