<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Payment $payment,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rentify - Payment Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $method = $this->payment->method instanceof \BackedEnum
            ? $this->payment->method->label()
            : $this->payment->method;

        return new Content(
            view: 'emails.payment-received',
            with: [
                'tenantName' => $this->payment->invoice->lease->tenant->user->name,
                'amount' => number_format($this->payment->amount, 2),
                'method' => $method,
                'reference' => $this->payment->reference ?? $this->payment->mpesa_receipt ?? 'N/A',
                'propertyName' => $this->payment->invoice->lease->unit->property->name,
                'unitName' => $this->payment->invoice->lease->unit->unit_number,
                'paidAt' => $this->payment->paid_at->format('d M Y, h:i A'),
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
