<?php

namespace Tests\Support;

use App\Traits\Reviewable;
use Illuminate\Notifications\Notifiable;

class ReviewableFake
{
    use Notifiable;
    use Reviewable;

    public array $sentNotifications = [];

    public function notify($instance)
    {
        $this->sentNotifications[] = $instance;
    }
}
