<?php

namespace App\Livewire;

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

class Frameworks extends Component
{
    use UserTrait;

    public ?string $frameworkId;

    public ?string $stageId;

    #[Computed]
    public function framework(): ?Framework
    {
        if (empty($this->frameworkId)) {
            $framework = Framework::first();
            $this->frameworkId = $framework->id;

            return $framework;
        }

        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function frameworks(): ?Collection
    {
        return Framework::all();
    }

    #[Computed]
    public function assessments(): ?Collection
    {
        if (empty($this->frameworkId)) {
            return $this->user()->assessments;
        }

        return $this->user()->assessments?->where('framework_id', $this->frameworkId);
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.frameworks');
    }
}
