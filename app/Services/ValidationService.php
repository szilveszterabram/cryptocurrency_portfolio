<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class ValidationService
{
    public function __construct()
    {
    }

    public function validateEntry(Request $request): array
    {
        return $request->validate([
            'portfolio_id' => 'required',
            'asset_short' => 'required|string',
            'asset_long' => 'required|string',
            'amount' => 'required|numeric|gt:0',
            'price_at_buy' => 'required|numeric',
        ]);
    }
}
