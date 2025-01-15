<?php

namespace App\Services;

use App\Models\User;

class ImpersonationService
{
    public function startImpersonation(User $user): void
    {
        session()->put('before_impersonation', auth()->user()->id);
        auth()->loginUsingId($user->id);
    }

    public function stopImpersonation(): bool
    {
        $user_id = session()->pull('before_impersonation');

        if ($user_id === null) {
            return false;
        }

        auth()->loginUsingId($user_id);
        return true;
    }
}
