<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    public function __construct(protected User $user) {}

    public function getUserBalance()
    {
        $authenticatedUser = $this->user->getAuthenticatedUser();
        return $authenticatedUser->balance;
    }

    public function updateUserBalance(float $amount): void
    {
        $authenticatedUser = $this->user->getAuthenticatedUser();
        $newBalance = $authenticatedUser->balance + $amount;
        $authenticatedUser->update(['balance' => $newBalance]);
    }
}
