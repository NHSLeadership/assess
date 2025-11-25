<?php

namespace App\Livewire;

use App\Notifications\ReviewRequestNotification;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class ReviewRequest extends Component
{
    public $assessmentId;

    public function render()
    {
        return view('livewire.review-request');
    }
}
