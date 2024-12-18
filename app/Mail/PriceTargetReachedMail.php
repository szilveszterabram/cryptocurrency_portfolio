<?php

namespace App\Mail;

use App\Models\PriceObservation;
use \Illuminate\Foundation\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PriceTargetReachedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected PriceObservation $priceObservation;
    protected array $asset;
    protected User $user;

    public function __construct(
        User $user,
        PriceObservation $priceObservation,
        array $asset
    ) {
        $this->priceObservation = $priceObservation;
        $this->asset = $asset;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@crypto.com', 'Cryptocurrency Portfolio Team'),
            subject: "Cryptocurrency Portfolio | Price Target reached on {$this->priceObservation['asset_id']}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.price_target_reached',
            text: 'emails.price_target_reached_plain',
            with: [
                'priceObservation' => $this->priceObservation,
                'asset' => $this->asset,
                'user' => $this->user,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
