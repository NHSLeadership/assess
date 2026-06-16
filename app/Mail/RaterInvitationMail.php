<?php

namespace App\Mail;

use App\Models\Assessment;
use App\Models\Rater;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RaterInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Assessment $assessment,
        public Rater $rater,
        public string $url
    ) {}

    public function build(): self
    {
        return $this
            ->subject('You have been invited to complete an assessment')
            ->markdown('mail.rater-invite');
    }
}
