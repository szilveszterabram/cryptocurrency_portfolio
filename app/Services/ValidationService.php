<?php

namespace App\Services;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class ValidationService
{
    public function __construct(protected Portfolio $portfolio) {}

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

    public function validatePortfolio(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255'
        ]);
    }

    public function validatePriceObservation(Request $request): array
    {
        return $request->validate([
            'target' => 'required|numeric|gt:0',
            'active' => 'required|boolean',
            'asset_id' => 'required',
        ]);
    }

    public function validateBalanceAddition(Request $request): array
    {
        return $request->validate([
            'balance' => 'required|numeric|gt:0',
        ]);
    }
}
