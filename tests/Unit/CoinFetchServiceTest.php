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

describe('Unit:CoinFetchService', function() {
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
});
