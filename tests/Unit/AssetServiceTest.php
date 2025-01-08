<?php

use App\Models\Asset;
use App\Services\AssetService;
use App\Services\CoinFetchService;
use Database\Seeders\AssetTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
   $this->assetService = new AssetService(new CoinFetchService());
});

describe('AssetService', function () {
    test('getAll returns all assets from the db in a paginated form', function () {
        $this->seed(AssetTestSeeder::class);

        $assets = $this->assetService->getAll();

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

    test('getAssetByAssetId returns an asset by its asset_id', function(Asset $factoryAsset) {
        $asset = $this->assetService->getAssetByAssetId('BTC');

        expect($asset)
            ->toBeInstanceOf(Asset::class)
            ->asset_id->toBe($factoryAsset['asset_id'])
            ->name->toBe($factoryAsset['name'])
            ->price_usd->toEqual($factoryAsset['price_usd'])
            ->icon_url->toBe($factoryAsset['icon_url'])
            ->type_is_crypto->toBe((int)$factoryAsset['type_is_crypto']);
    })->with('asset for get asset by asset id');
});
