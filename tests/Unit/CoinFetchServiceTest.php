<?php

use App\Enums\CoinApiEndpoint;
use App\Services\CoinFetchService;
use function PHPUnit\Framework\assertEquals;

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
});
