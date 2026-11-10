<?php

namespace App\Services;

use App\Mail\FormSubmissionMail;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class FormSubmissionMailer
{
    /**
     * @param  array<string, string|int|null>  $details
     */
    public function sendToSubmitterAndReceiver(string $formName, string $submitterEmail, array $details): void
    {
        $receiverEmail = getSetting('email');

        if (! is_string($receiverEmail) || filter_var($receiverEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('A valid contact email address must be configured before form submissions can be emailed.');
        }

        Mail::to($submitterEmail)->send(new FormSubmissionMail($formName, $details, true));
        Mail::to($receiverEmail)->send(new FormSubmissionMail($formName, $details, false));
    }
}
