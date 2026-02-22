<?php

namespace App\Mail;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public MaintenanceRequest $maintenanceRequest,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rentify - Maintenance Request Update',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $status = $this->maintenanceRequest->status instanceof \BackedEnum
            ? $this->maintenanceRequest->status->label()
            : $this->maintenanceRequest->status;

        return new Content(
            view: 'emails.maintenance-updated',
            with: [
                'tenantName' => $this->maintenanceRequest->tenant->user->name,
                'title' => $this->maintenanceRequest->title,
                'status' => $status,
                'propertyName' => $this->maintenanceRequest->unit->property->name,
                'unitName' => $this->maintenanceRequest->unit->unit_number,
                'resolutionNotes' => $this->maintenanceRequest->resolution_notes,
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
