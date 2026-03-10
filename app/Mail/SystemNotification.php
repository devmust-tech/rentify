<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SystemNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $notificationSubject;
    public string $messageBody;
    public ?string $actionUrl;
    public ?string $actionText;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $subject,
        string $messageBody,
        ?string $actionUrl = null,
        ?string $actionText = null,
    ) {
        $this->notificationSubject = $subject;
        $this->messageBody = $messageBody;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rentify - ' . $this->notificationSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.system-notification',
            with: [
                'subject' => $this->notificationSubject,
                'messageBody' => $this->messageBody,
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->actionText,
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
