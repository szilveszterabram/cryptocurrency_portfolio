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

    public function validateBalanceAddition(Request $request): array
    {
        return $request->validate([
            'balance' => 'required|numeric|gt:0',
        ]);
    }
}
