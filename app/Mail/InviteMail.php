<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to join Cryptocurrency Portfolio',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite',
            with: [
                'token' => $this->token,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
