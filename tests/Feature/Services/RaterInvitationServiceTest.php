<?php

use App\Models\Assessment;
use App\Models\Rater;
use App\Services\RaterInvitationService;
use Illuminate\Support\Facades\Mail;
use App\Mail\RaterInvitationMail;

test('it sends an invitation and sets invited_at', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create();
    $rater = Rater::factory()->create();

    // attach rater
    $assessment->raters()->attach($rater->id, [
        'invited_at' => null,
    ]);

    app(RaterInvitationService::class)->send($assessment, $rater);

    // Assert invited_at updated
    $this->assertDatabaseHas('assessment_rater', [
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
    ]);

    $pivot = $assessment->raters()
        ->where('raters.id', $rater->id)
        ->first()
        ->pivot;

    expect($pivot->invited_at)->not->toBeNull();

    // Assert email sent
    Mail::assertSent(RaterInvitationMail::class, function ($mail) use ($rater) {
        return $mail->hasTo($rater->email);
    });
});

test('invitation email contains signed url', function () {
    Mail::fake();

    $assessment = Assessment::factory()->create();
    $rater = Rater::factory()->create();

    $assessment->raters()->attach($rater->id);

    app(RaterInvitationService::class)->send($assessment, $rater);

    Mail::assertSent(RaterInvitationMail::class, function ($mail) use ($assessment, $rater) {
        $mail->build();

        $html = $mail->render();

        return str_contains($html, "assessment-rater/{$assessment->id}/{$rater->id}")
            && str_contains($html, 'signature=');
    });
});

test('status precedence is correct', function () {
    $assessment = Assessment::factory()->create();
    $rater = Rater::factory()->create();

    $assessment->raters()->attach($rater->id, [
        'invited_at' => now(),
        'started_at' => now(),
        'submitted_at' => now(),
    ]);

    $pivot = $assessment->raters()->first()->pivot;

    expect($pivot->getStatus())->toBe('Completed');
});
