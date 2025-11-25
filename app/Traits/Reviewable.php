<?php

namespace App\Traits;

use App\Notifications\ReviewRequestNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

trait Reviewable
{
    public function sendReviewRequestLink(Model $model): void
    {
        $this->notify(new ReviewRequestNotification($model));
    }

    public function getLink(Model $model): string
    {
        return URL::signedRoute('review-request', [
            'model'  => $model
        ]);
    }
}
