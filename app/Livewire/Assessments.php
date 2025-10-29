<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Stage;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Assessments extends Component
{
    public ?string $stageId;

    #[Computed]
    public function assessments(): Collection
    {
        if (empty($this->stageId) || !is_numeric($this->stageId)) {
            return Assessment::all();
        }

        return Assessment::where('stage_id', $this->stageId)->get();
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return Assessment::find($this->assessmentId);
    }

    #[Computed]
    public function stage(): ?Stage
    {
        if (empty($this->stageId)) {
            return null;
        }

        return Stage::find($this->stageId);
    }

    public function render()
    {
        return view('livewire.assessments');
    }
}
