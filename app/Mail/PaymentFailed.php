<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Organization $org) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Action Required: Subscription Payment Failed');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-failed',
            with: [
                'orgName'    => $this->org->name,
                'ownerName'  => $this->org->owner?->name ?? 'there',
                'billingUrl' => $this->org->subdomainUrl() . '/agent/billing',
            ],
        );
    }
}
