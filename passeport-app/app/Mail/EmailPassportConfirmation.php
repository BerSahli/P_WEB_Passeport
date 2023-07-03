<?php

namespace App\Mail;

use App\Models\Passport;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailPassportConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    
    public $passport;
    public $confirmationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Passport $passport, $confirmationUrl)
    {
        $this->passport = $passport;
        $this->confirmationUrl = $confirmationUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('bertrand.sahli@eduvaud.ch', 'Bertrand Sahli'),
            subject: 'Confirmation de modification de passeport',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.passport_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
