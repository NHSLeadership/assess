<?php

use App\Models\User;
use App\Notifications\ReviewRequestNotification;
use Tests\Support\ReviewableFake;

test('sendReviewRequestLink sends ReviewRequestNotification', function () {
    $fake = new ReviewableFake;

    $model = new User; // any Eloquent model works

    $fake->sendReviewRequestLink($model);

    expect($fake->sentNotifications)->toHaveCount(1)
        ->and($fake->sentNotifications[0])->toBeInstanceOf(ReviewRequestNotification::class);
});
