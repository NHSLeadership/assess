<?php

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
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
        'type' => RaterType::Peer->value,
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

    $assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer->value,
    ]);

    app(RaterInvitationService::class)->send($assessment, $rater);

    Mail::assertSent(RaterInvitationMail::class, function ($mail) use ($assessment, $rater) {
        $mail->build();

        $html = $mail->render();

        return str_contains($html, "rate-assessment/{$assessment->id}/{$rater->id}")
            && str_contains($html, 'signature=');
    });
});

test('status precedence is correct', function () {
    $assessment = Assessment::factory()->create();
    $rater = Rater::factory()->create();

    $assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer->value,
        'invited_at' => now(),
        'started_at' => now(),
        'submitted_at' => now(),
    ]);

    $pivot = $assessment->raters()->first()->pivot;

    expect($pivot->getStatus())->toBe('Completed');
});

test('does not send an invitation to a completed rater', function () {
    Mail::fake();

    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
        'email' => 'completed-rater@example.com',
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => RaterType::Peer,
        'invited_at' => now()->subDay(),
        'submitted_at' => now(),
    ]);

    expect(fn() => app(RaterInvitationService::class)->send(
        $assessment,
        $rater,
    ))->toThrow(
        InvalidArgumentException::class,
        'A completed rater cannot be invited again.',
    );

    Mail::assertNotSent(RaterInvitationMail::class);
});
