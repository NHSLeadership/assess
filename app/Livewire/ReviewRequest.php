<?php

namespace App\Livewire;

use Livewire\Component;

class ReviewRequest extends Component
{
    public $assessmentId;

    public function render()
    {
        return view('livewire.review-request');
    }
}
