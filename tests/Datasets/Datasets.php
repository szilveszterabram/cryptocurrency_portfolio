<?php

use App\Models\Asset;
use App\Models\Entry;
use App\Models\Portfolio;

dataset('asset for get asset by asset id', [
    [fn() => Asset::factory()->create([
        'id' => 0,
        'asset_id' => 'BTC',
        'name' => 'Bitcoin',
        'price_usd' => 101707.81689619,
        'type_is_crypto' => true,
        'icon_url' => 'https://s3.eu-central-1.amazonaws.com/bbxt-static-icons/type-id/png_512/4caf2b16a0174e26a3482cea69c34cba.png',
    ])],
]);

dataset('assets for get assets for entries', [
    [fn() => Asset::factory()->create([
        'id' => 1,
        'asset_id' => 'BTC',
        'name' => 'Bitcoin',
        'price_usd' => 101707.81689619,
        'type_is_crypto' => true,
        'icon_url' => 'https://s3.eu-central-1.amazonaws.com/bbxt-static-icons/type-id/png_512/4caf2b16a0174e26a3482cea69c34cba.png',
    ]),
    [fn() => Asset::factory()->create([
        'id' => 2,
        'asset_id' => 'ETH',
        'name' => 'Ethereum',
        'price_usd' => 3703.0860963047,
        'type_is_crypto' => true,
        'icon_url' => 'https://s3.eu-central-1.amazonaws.com/bbxt-static-icons/type-id/png_512/604ae4533d9f4ad09a489905cce617c2.png',
    ])],
    [fn() => Entry::factory()->create([
        'portfolio_id' => Portfolio::factory()->create()->id,
        'asset_short' => 'BTC',
        'asset_long' => 'Bitcoin',
        'amount' => 3,
        'price_at_buy' => 101707.81689619,
    ]),
    [fn() => Entry::factory()->create([
        'portfolio_id' => Portfolio::factory()->create()->id,
        'asset_short' => 'ETH',
        'asset_long' => 'Etherium',
        'amount' => 3,
        'price_at_buy' => 0.001,
    ])
]]]]);


