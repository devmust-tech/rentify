<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $inviteUrl,
        public string $orgName,
        public string $role = 'user',
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
            view: 'emails.user-invitation',
            with: [
                'userName'  => $this->user->name,
                'orgName'   => $this->orgName,
                'inviteUrl' => $this->inviteUrl,
                'role'      => $this->role,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
