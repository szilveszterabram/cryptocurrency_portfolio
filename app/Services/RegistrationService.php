<?php

namespace App\Services;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationService
{
    public function validateToken(Request $request): bool
    {
        if ($request->has('invite_token')) {
            $invite = Invitation::where('token', $request->get('invite_token'))->first();
            if ($invite && $invite->is_used === false) {
                return true;
            }
        }
        return false;
    }

    public function useInvite(Request $request): void
    {
        $invite = Invitation::where('token', $request->get('invite_token'))->first();
        $invite?->update(['is_used' => true]);
    }
}
