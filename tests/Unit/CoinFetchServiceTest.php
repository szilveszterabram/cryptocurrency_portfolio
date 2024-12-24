<?php

use App\Enums\CoinApiEndpoint;
use App\Models\Asset;
use App\Services\CoinFetchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
   $this->coinFetchService = new CoinFetchService();
});

describe('coinFetchService', function() {
    test('getHeaders returns an array with necessary header information', function() {
        $headers = $this->coinFetchService->getHeaders();

        expect($headers)
            ->tobeArray()
            ->toHaveKey('X-CoinAPI-key')
        ->Accept
            ->toBe('application/json');
    });

    test('getBaseUrl returns the base url for coin api requests', function() {
        $baseUrl = $this->coinFetchService->getBaseUrl();

        expect($baseUrl)->toBeUrl();
    });

    test('getRoute returns routes based on enum values', function() {
        $assetRoute = $this->coinFetchService->getRoute(CoinApiEndpoint::Assets);
        $iconRoute = $this->coinFetchService->getRoute(CoinApiEndpoint::Icons);

        expect($assetRoute)
            ->toBeString()
            ->toStartWith('/')
        ->and($iconRoute)
            ->toBeString()
            ->toStartWith('/');
    });

    test('getFullUrl uses base functions correctly and returns an api endpoint', function() {
        $baseUrl = $this->coinFetchService->getBaseUrl();
        $assetRoute = $this->coinFetchService->getRoute(CoinApiEndpoint::Assets);

        $assetUrl = $this->coinFetchService->getFullUrl(CoinApiEndpoint::Assets);
        $assetUrlWithSingleParam = $this->coinFetchService->getFullUrl(CoinApiEndpoint::Assets, ['BTC']);
        $assetUrlWithTwoParams = $this->coinFetchService->getFullUrl(CoinApiEndpoint::Assets, ['BTC', 'ETH']);

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
        $icons = $this->coinFetchService->fetchData(CoinApiEndpoint::Icons);
        $iconSample = $icons[0];

        expect($icons)
            ->not->toBeEmpty()
            ->toBeArray()
        ->and($iconSample)
            ->toHaveKeys(['asset_id', 'url']);
    });

    test('fetchAssetById fetches a single asset correctly based on asset id', function() {
        $assetId = 'BTC';
        $asset = $this->coinFetchService->fetchAssetById($assetId);

        // NOTE:
        // The price_usd field does not exist on every asset.
        // You may get assertion error if you try it on something else.
        expect($asset)
            ->not->toBeNull()
            ->tohaveKeys(['asset_id', 'name', 'type_is_crypto', 'price_usd']);
    });

    test('update takes an array of assets, updates the corresponding db model or create a new one if it does not exist', function() {
        $this->coinFetchService->update([
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
            ->toEqual(100.0)
        ->and($enjAsset->price_usd)
            ->toEqual(0.2064);
    })->with('assets for update');

    test('updateIcons updates existing database asset icons', function(
        $assetForUpdate
    ) {
        expect($assetForUpdate)->toBeInstanceOf(Asset::class);
        $this->coinFetchService->updateIcons([
            [
                'asset_id' => 'BTC',
                'url' => 'new-asset-url.png'
            ]
        ]);

        $updatedAsset = Asset::where('asset_id', 'BTC')->first();

        expect($updatedAsset)
            ->toBeInstanceOf(Asset::class)
        ->asset_id
            ->toBe('BTC')
        ->icon_url
            ->toBe('new-asset-url.png');
    })->with('assets for updateIcons');
});
