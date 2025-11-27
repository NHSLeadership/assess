<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\FrameworkVariantOption;
use App\Models\Rater;
use App\Services\UserAssessmentVariantSelectionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Traits\UserTrait;

class Variants extends Component
{
    use UserTrait;
    public ?string $frameworkId;
    public ?string $stageId;
    public ?string $assessmentId;
    public ?array $data;

    public function mount()
    {
        $this->data = $this->variantSelections()->toArray();
    }
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
    public function attributes(): ?Collection
    {
        if (empty($this->frameworkId) || ! is_numeric($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->variantAttributes()->get();
    }

    #[Computed]
    public function variant(): ?FrameworkVariantOption
    {
        if (empty($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->stages()->first()->options()->where('id', $this->stageId)->first();
    }

    #[Computed]
    public function frameworks(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Framework|null
    {
        return Framework::with(['variantAttributes.options'])->find($this->frameworkId);
    }

    #[Computed]
    public function variantSelections(): Collection
    {
        return $this->user()->assessments()->where('id', $this->assessmentId)->first()
            ->variantSelections->pluck('framework_variant_option_id', 'attribute.key');
    }

    public function store(): void
    {

          $attributes = $this->attributes()?->keyBy('key');
//          dd($attributes);
//        $questions = $this->nodeQuestions()?->keyBy('name');

        foreach ($this->data as $key => $value) {
            if (isset($attributes[$key])) {
                UserAssessmentVariantSelectionService::updateOrCreate($value, $attributes[$key], $this->assessmentId);
            }
        }
        $this->redirect(route('questions', $this->assessmentId));
//        if ($this->paginatedQuestions()->hasMorePages()) {
//            $this->nextPage(pageName: $this->pageName);
//        } else {
//            $this->resetPage(pageName: $this->pageName);
//            $this->nodes->next();
//            $this->nodeId = $this->nodes->key();
//        }
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
        return view('livewire.variants');
    }
}
