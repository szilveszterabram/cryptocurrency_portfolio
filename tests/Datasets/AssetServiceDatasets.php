<?php

use App\Models\Asset;

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



