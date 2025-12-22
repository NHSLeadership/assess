<?php

namespace Tests\Support;

use App\Traits\Reviewable;
use Illuminate\Notifications\Notifiable;

class ReviewableFake
{
    use Reviewable;
    use Notifiable;

    public array $sentNotifications = [];

    public function notify($instance)
    {
        $this->sentNotifications[] = $instance;
    }
}
