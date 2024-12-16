<?php

namespace App\Services;

use App\Enums\CoinApiEndpoint;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinFetchService
{
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
            CoinApiEndpoint::Icons => $this->getRoute(CoinApiEndpoint::Assets) . config('services.api.icons'),
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
}
