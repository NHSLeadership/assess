<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;

class AssessmentHome extends Component
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
            return $this->user->assessments;
        }

        return $this->user->assessments?->where('framework_id', $this->frameworkId);
    }

    public function newAssessment(): void
    {
        $assessment = new Assessment([
            'framework_id' => $this->frameworkId ?? null,
            'user_id' => $this->user->id,
        ]);
        $assessment->save();

        if ($assessment->exists) {
            $this->redirect(route('assessments', $assessment->id));
        } else {
            session()->flash('message', __('Could not initialise new assessment. Please try again later.'));
        }
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.assessment-home');
    }
}
