<?php

use App\Models\Asset;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use Database\Seeders\AssetTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

uses(RefreshDatabase::class);

describe('assetService', function () {
    test('getAll returns all assets from the db in a paginated form', function () {
        $this->seed(AssetTestSeeder::class);

        $assetService = new AssetService(new CoinFetchService());
        $assets =  $assetService->getAll();

        expect($assets)->toBeInstanceOf(LengthAwarePaginator::class);

        $prices = $assets->pluck('price_usd');
        $icons = $assets->pluck('icon_url');

        expect($prices)
            ->each
            ->tobeGreaterThan(-1)
            ->and($icons)
            ->each
            ->not
            ->toBeNull();
    });

    test('getAssetByAssetId returns an asset by its asset_id', function($assetForGetAssetByAssetId) {
        $assetService = new AssetService(new CoinFetchService());

        $asset = $assetService->getAssetByAssetId('BTC');

        expect($asset)
            ->toBeInstanceOf(Asset::class)
            ->and($asset['asset_id'])
            ->toBe($assetForGetAssetByAssetId['asset_id'])
            ->and($asset['name'])
            ->toBe($assetForGetAssetByAssetId['name'])
            ->and($asset['price_usd'])
            ->toBe($assetForGetAssetByAssetId['price_usd'])
            ->and($asset['icon_url'])
            ->toBe($assetForGetAssetByAssetId['icon_url'])
            ->and($assetForGetAssetByAssetId['type_is_crypto'])
            ->toBe($assetForGetAssetByAssetId['type_is_crypto']);

    })->with('asset for get asset by asset id');
});
