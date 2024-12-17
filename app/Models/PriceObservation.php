<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceObservation extends Model
{
    protected $fillable = [
        'target',
        'active'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
