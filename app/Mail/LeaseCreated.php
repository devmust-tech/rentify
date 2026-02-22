<?php

namespace App\Mail;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaseCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Lease $lease,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rentify - New Lease Agreement',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lease-created',
            with: [
                'tenantName' => $this->lease->tenant->user->name,
                'propertyName' => $this->lease->unit->property->name,
                'unitName' => $this->lease->unit->unit_number,
                'rentAmount' => number_format($this->lease->rent_amount, 2),
                'deposit' => number_format($this->lease->deposit, 2),
                'startDate' => $this->lease->start_date->format('d M Y'),
                'endDate' => $this->lease->end_date->format('d M Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
