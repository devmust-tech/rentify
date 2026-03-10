<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public string $inviteUrl,
        public string $orgName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to {$this->orgName} on Rentify",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tenant-invitation',
            with: [
                'tenantName' => $this->tenant->user->name,
                'orgName'    => $this->orgName,
                'inviteUrl'  => $this->inviteUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
