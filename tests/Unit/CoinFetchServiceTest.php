<?php

use App\Enums\CoinApiEndpoint;
use App\Models\Asset;
use App\Services\CoinFetchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\assertEquals;

uses(RefreshDatabase::class);

describe('coinFetchService', function() {
    test('getHeaders returns an array with necessary header information', function() {
        $coinFetchService = new CoinFetchService();

        $headers = $coinFetchService->getHeaders();

        expect($headers)
            ->tobeArray()
        ->and($headers['Accept'])
            ->toBe('application/json')
        ->and($headers)
            ->tohaveKey('X-CoinAPI-key');
    });

    test('getBaseUrl returns the base url for coin api requests', function() {
        $coinFetchService = new CoinFetchService();

        $baseUrl = $coinFetchService->getBaseUrl();

        expect($baseUrl)
            ->toBeString()
        ->and($baseUrl)
            ->toStartWith('https://');
    });

    test('getRoute returns routes based on enum values', function() {
        $coinFetchService = new CoinFetchService();

        $assetRoute = $coinFetchService->getRoute(CoinApiEndpoint::Assets);
        $iconRoute = $coinFetchService->getRoute(CoinApiEndpoint::Icons);

        expect($assetRoute)
            ->toBeString()
        ->and($iconRoute)
            ->toBeString()
        ->and($assetRoute)
            ->toStartWith('/')
        ->and($iconRoute)
            ->toStartWith('/');
    });

    test('getFullUrl uses base functions correctly and returns an api endpoint', function() {
        $coinFetchService = new CoinFetchService();

        $baseUrl = $coinFetchService->getBaseUrl();
        $assetRoute = $coinFetchService->getRoute(CoinApiEndpoint::Assets);

        $assetUrl = $coinFetchService->getFullUrl(CoinApiEndpoint::Assets);
        $assetUrlWithSingleParam = $coinFetchService->getFullUrl(CoinApiEndpoint::Assets, ['BTC']);
        $assetUrlWithTwoParams = $coinFetchService->getFullUrl(CoinApiEndpoint::Assets, ['BTC', 'ETH']);

        expect($assetUrl)
            ->toBeString()
        ->and($assetUrlWithSingleParam)
            ->toBeString()
        ->and($assetUrlWithTwoParams)
            ->toBeString();

        assertEquals($assetUrl, $baseUrl . $assetRoute);
        assertEquals($assetUrlWithSingleParam, "{$baseUrl}{$assetRoute}/BTC");
        assertEquals($assetUrlWithTwoParams, "{$baseUrl}{$assetRoute}/BTC,ETH");
    });

    test('fetchData fetches from all endpoints correctly and returns them in an array', function() {
        $coinFetchService = new CoinFetchService();

        $icons = $coinFetchService->fetchData(CoinApiEndpoint::Icons);
        $iconSample = $icons[0];

        expect($icons)
            ->not
            ->toBeEmpty()
        ->and($icons)
            ->toBeArray()
        ->and($iconSample)
            ->toHaveKeys(['asset_id', 'url']);
    });

    test('fetchAssetById fetches a single asset correctly based on asset id', function() {
        $coinFetchService = new CoinFetchService();

        $assetId = 'BTC';
        $asset = $coinFetchService->fetchAssetById($assetId);

        // NOTE:
        // The price_usd field does not exist on every asset.
        // You may get assertion error if you try it on something else.
        expect($asset)
            ->not
            ->toBeNull()
        ->and($asset)
            ->tohaveKeys(['asset_id', 'name', 'type_is_crypto', 'price_usd']);
    });

    test('update takes an array of assets, updates the corresponding db model or create a new one if it does not exist', function() {
        $coinFetchService = new CoinFetchService();

        $coinFetchService->update([
            [
                'asset_id' => 'BTC',
                'name' => 'Bitcoin',
                'price_usd' => 100,
                'type_is_crypto' => 1,
            ],
            [
                'asset_id' => 'ETH',
                'name' => 'Ethereum',
                'price_usd' => 335.95,
                'type_is_crypto' => true
            ],
            [
                'asset_id' => 'ENJ',
                'name' => 'Enjin',
                'price_usd' => 0.2064,
                'type_is_crypto' => true
            ]
        ]);

        $dbAssets = Asset::whereIn('asset_id', ['BTC', 'ETH', 'ENJ'])->get();

        $btcAsset = $dbAssets->where('asset_id', 'BTC')->first();
        $enjAsset = $dbAssets->where('asset_id', 'ENJ')->first();

        expect($dbAssets)
            ->toBeInstanceOf(Collection::class)
        ->and(count($dbAssets))
            ->toBe(3)
        ->and($btcAsset->price_usd)
            ->toBe(100.0)
        ->and($enjAsset->price_usd)
            ->toBe(0.2064);
    })->with('assets for update');

    test('updateIcons updates existing database asset icons', function(
        $assetForUpdate
    ) {
        expect($assetForUpdate)->toBeInstanceOf(Asset::class);

        $coinFetchService = new CoinFetchService();

        $coinFetchService->updateIcons([
            [
                'asset_id' => 'BTC',
                'url' => 'new-asset-url.png'
            ]
        ]);

        $updatedAsset = Asset::where('asset_id', 'BTC')->first();

        expect($updatedAsset)
            ->toBeInstanceOf(Asset::class)
        ->and($updatedAsset->asset_id)
            ->toBe('BTC')
        ->and($updatedAsset->icon_url)
            ->toBe('new-asset-url.png');
    })->with('assets for updateIcons');
});
