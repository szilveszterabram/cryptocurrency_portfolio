<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_short',
        'asset_long',
        'amount',
        'price_at_buy',
    ];

    public function create(Portfolio $portfolio, array $data): Entry
    {
        return $portfolio->entries()->create($data);
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
