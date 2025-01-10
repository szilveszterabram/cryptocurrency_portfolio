<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;

class ValidationService
{
    public function __construct(
        protected Portfolio $portfolio,
        protected User $user,
    ) {}

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
