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
        $frameworkName = $this->assessment->framework?->name
            ?? 'assessment framework';

        return <<<MARKDOWN
Dear {$this->rater->name},

{$this->subjectName} has invited you to provide feedback as part of their assessment against the {$frameworkName}.

Your feedback will form part of their 360 assessment, helping them identify strengths and development opportunities as part of their ongoing professional development.

Feedback should take around 15–20 minutes and can be completed across multiple sessions.

Keep this email so you can use the link to resume your feedback later.

Do not share this link with anyone else.
MARKDOWN;
    }
}
