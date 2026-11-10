<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FormSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, string|int|null>  $details
     */
    public function __construct(
        public string $formName,
        public array $details,
        public bool $isConfirmation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isConfirmation
                ? 'We received your '.ucfirst($this->formName)
                : 'New '.ucfirst($this->formName),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.form-submission',
            with: [
                'formName' => $this->formName,
                'details' => $this->details,
                'isConfirmation' => $this->isConfirmation,
            ],
        );
    }
}
