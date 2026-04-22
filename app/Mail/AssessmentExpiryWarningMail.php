<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessmentExpiryWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Carbon $expiresAt,

    ) {}

    public function build(): AssessmentExpiryWarningMail
    {
        return $this
            ->subject('Your assessment will be deleted soon')
            ->markdown('mail.assessment-expiry-warning');
    }
}
