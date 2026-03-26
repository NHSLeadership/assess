<?php

namespace App\Livewire;

use Livewire\Component;

class ReviewRequest extends Component
{
    public $assessmentId;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.review-request');
    }
}
