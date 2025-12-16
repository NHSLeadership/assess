<?php

namespace App\Livewire;

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;

class ContactUs extends Component
{

    public function render()
    {
        return view('livewire.contact-us');
    }
}
