<?php

namespace App\Notifications;

use App\Models\Assessment;
use App\Models\Rater;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssessmentCompleted extends Notification
{
    use Queueable;

    public function __construct(
        public Assessment $assessment,
        public Rater $subject,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Assessment completed')
            ->markdown('mail.assessment-completed', [
                'assessment' => $this->assessment,
                'subject' => $this->subject,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
