<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public string $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment, string $pdfPath)
    {
        $this->payment = $payment->load([
            'booking.service',
            'booking.customer',
            'booking.provider',
        ]);

        $this->pdfPath = $pdfPath;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmation - MyFixNow',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment.confirmation',
            with: [
                'payment' => $this->payment,
                'booking' => $this->payment->booking,
                'customer' => $this->payment->booking->customer,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Receipt.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
