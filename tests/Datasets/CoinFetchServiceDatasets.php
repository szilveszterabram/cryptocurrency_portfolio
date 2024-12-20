<?php

use App\Models\Asset;

dataset('assets for update', [
    fn () => Asset::factory()->create([
        'asset_id' => 'BTC',
        'name' => 'Bitcoin',
        'price_usd' => 100000.54321,
        'type_is_crypto' => true
    ]),
    fn () => Asset::factory()->create([
        'asset_id' => 'ETH',
        'name' => 'Ethereum',
        'price_usd' => 3359.5,
        'type_is_crypto' => true
    ]),
]);

dataset('assets for updateIcons', [
    fn () => Asset::factory()->create([
        'asset_id' => 'BTC',
        'name' => 'Bitcoin',
        'price_usd' => 100000.54321,
        'type_is_crypto' => true,
        'icon_url' => 'old-asset-url.png'
    ]),
]);
