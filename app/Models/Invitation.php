<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Random\RandomException;

class Invitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'is_used',
    ];

    protected $casts = [
        'is_used' => 'boolean',
    ];

    /**
     * @throws RandomException
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
