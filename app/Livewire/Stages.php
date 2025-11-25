<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\FrameworkVariantOption;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Traits\UserTrait;

class Stages extends Component
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
    public function options(): ?Collection
    {
        if (empty($this->frameworkId) || ! is_numeric($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->stages()->first()->options()->get();
    }

    #[Computed]
    public function stage(): ?FrameworkVariantOption
    {
        if (empty($this->stageId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->stages()->first()->options()->where('id', $this->stageId)->first();
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

    public function render()
    {
        return view('livewire.stages');
    }
}
