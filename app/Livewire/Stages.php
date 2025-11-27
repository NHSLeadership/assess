<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\FrameworkVariantOption;
use App\Models\Rater;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

    #[Computed]
    public function frameworks(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Framework|null
    {
        return Framework::with(['variantAttributes.options'])->find($this->frameworkId);
    }

    public function store(): void
    {
        $rules = $this->getRules();
        if (!empty($rules)) {
            $this->validate($rules);
        }

        $questions = $this->nodeQuestions()?->keyBy('name');
        // TODO - Remove users table and the constrain in the raters table. Have user_id should be sso user id.
        $rater = Rater::firstOrCreate([
            'user_id' => 1,
            'name' => '',
        ]);

        foreach ($this->data as $name => $values) {
            if (isset($questions[$name])) {
                UserResponseService::updateOrCreate($values, $questions[$name], $this->assessmentId, $rater->id);
            }
        }

        if ($this->paginatedQuestions()->hasMorePages()) {
            $this->nextPage(pageName: $this->pageName);
        } else {
            $this->resetPage(pageName: $this->pageName);
            $this->nodes->next();
            $this->nodeId = $this->nodes->key();
        }
    }

//    public function newAssessment(): void
//    {
//        try {
//            DB::transaction(function () {
//                // Create the assessment
//                $assessment = Assessment::create([
//                    'framework_id' => $this->frameworkId ?? null,
//                    'user_id'      => $this->user()->user_id,
//                ]);
//
//                // Ensure rater record exists (no duplicates)
//                $rater = Rater::firstOrCreate(
//                    ['user_id' => $this->user()->user_id],
//                    ['created_at' => now()] // optional defaults
//                );
//
//                // Link rater to this assessment (avoid duplicates too)
//                AssessmentRater::firstOrCreate([
//                    'assessment_id' => $assessment->id,
//                    'rater_id'      => $rater->id,
//                ]);
//
//                // Redirect only after transaction succeeds
//                $this->redirect(route('assessments', $assessment->id));
//            }, 3); // retry count for deadlocks.
//        } catch (\Throwable $e) {
//            report($e); // log the error for debugging
//            $this->dispatch(
//                'alert',
//                type: 'error',
//                message: __('alerts.errors.assessment-initialise'),
//            );
//        }
//    }

    public function render()
    {
        return view('livewire.stages');
    }
}
