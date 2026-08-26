<?php

namespace App\Notifications;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RaterFeedbackSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public Assessment $assessment,
    ) {
    }
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('360 feedback received'))
            ->markdown(
                'mail.rater-feedback-submitted',
                [
                    'assessment' => $this->assessment,
                ]
            );
    }
}
