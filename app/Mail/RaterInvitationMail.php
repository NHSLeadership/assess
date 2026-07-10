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
        public string $url,
        public string $subjectName,
        public string $role,
        public ?string $groupName = null,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Feedback request')
            ->markdown('mail.rater-invite', [
                'intro' => $this->invitationIntro(),
            ]);
    }

    protected function invitationIntro(): string
    {
        return $this->assessment->framework?->rater_invitation_intro
            ?? $this->defaultInvitationIntro();
    }

    protected function defaultInvitationIntro(): string
    {
        $frameworkName = $this->assessment->framework?->name ?? 'assessment framework';

        $intro = "Dear {$this->rater->name},"
        . "<p> {$this->subjectName} has invited you to provide feedback as part of their assessment against the {$frameworkName}.</p>"
        . "<p>Your feedback will form part of their 360 assessment, helping them identity strengths and development opportunities as part of their ongoing professional development.</p>"
        . "<p>Feedback should take around 15-20 minutes and can be completed across multiple sessions.</p>"
        . "<p>Keep this email so you can reuse the link to resume feedback.</p>"
        . "<p>Do not share with anyone else.</p>";

        return $intro;
    }
}
