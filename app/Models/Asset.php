<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
