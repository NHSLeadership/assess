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
            return $this->user->assessments;
        }

        return $this->user->assessments?->where('framework_id', $this->frameworkId);
    }

    public function newAssessment(): void
    {
        try {
            DB::transaction(function () {
                // Create the assessment
                $assessment = Assessment::create([
                    'framework_id' => $this->frameworkId ?? null,
                    'user_id'      => $this->user()->user_id,
                ]);

                // Ensure rater record exists (no duplicates)
                $rater = Rater::firstOrCreate(
                    ['user_id' => $this->user()->user_id],
                    ['created_at' => now()] // optional defaults
                );

                // Link rater to this assessment (avoid duplicates too)
                AssessmentRater::firstOrCreate([
                    'assessment_id' => $assessment->id,
                    'rater_id'      => $rater->id,
                ]);

                // Redirect only after transaction succeeds
                $this->redirect(route('assessments', $assessment->id));
            }, 3); // retry count for deadlocks.
        } catch (\Throwable $e) {
            report($e); // log the error for debugging
            $this->dispatch(
                'alert',
                type: 'error',
                message: __('alerts.errors.assessment-initialise'),
            );
        }
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.frameworks');
    }
}
