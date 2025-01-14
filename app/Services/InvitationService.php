<?php

namespace App\Services;

use App\Mail\InviteMail;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Random\RandomException;

class InvitationService
{
    /**
     * @throws RandomException
     */
    public function createInvitation(string $email): string
    {
        $token = Invitation::generateToken();

        Invitation::create([
            'email' => $email,
            'token' => $token,
        ]);

        return $token;
    }

    public function sendInvitation(string $email, string $token): void
    {
        Mail::to($email)->send(new InviteMail($token));
    }
}
