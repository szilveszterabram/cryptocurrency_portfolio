<?php

namespace App\Models;

use App\Observers\AssetObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([AssetObserver::class])]
class Asset extends Model
{
    protected $fillable = [
        'asset_id',
        'name',
        'price_usd',
        'type_is_crypto',
        'icon_url'
    ];
}
