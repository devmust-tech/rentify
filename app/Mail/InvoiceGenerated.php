<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceGenerated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Invoice $invoice,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $month = $this->invoice->due_date->format('F');
        $year = $this->invoice->due_date->format('Y');

        return new Envelope(
            subject: "Rentify - Invoice for {$month} {$year}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-generated',
            with: [
                'tenantName' => $this->invoice->lease->tenant->user->name,
                'amount' => number_format($this->invoice->amount, 2),
                'propertyName' => $this->invoice->lease->unit->property->name,
                'unitName' => $this->invoice->lease->unit->unit_number,
                'dueDate' => $this->invoice->due_date->format('d M Y'),
                'month' => $this->invoice->due_date->format('F Y'),
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
