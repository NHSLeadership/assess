<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\FrameworkVariantOption;
use App\Models\Node;
use App\Models\Rater;
use App\Services\UserAssessmentVariantSelectionService;
use App\Traits\RedirectAssessment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Traits\UserTrait;

class Variants extends Component
{
    use UserTrait;
    use RedirectAssessment;

    public ?string $frameworkId;
    public ?string $assessmentId = null;
    public ?array $data;
    public ?string $back = null;

    public function mount($frameworkId = null, $assessmentId = null)
    {
        $this->frameworkId = $frameworkId;
        $this->assessmentId = $assessmentId;

        // Validate frameworkId
        if (
            empty($this->frameworkId) ||
            !is_numeric($this->frameworkId) ||
            !Framework::whereKey((int) $this->frameworkId)->exists()
        ) {
            return redirect()->route('frameworks');
        }

        // Validate assessmentId
        if (
            !empty($this->assessmentId) &&
            (!is_numeric($this->assessmentId) ||
            !Assessment::whereKey($this->assessmentId)->exists())
        ) {
            return redirect()->route('frameworks');
        }

        //Redirect to summary if already submitted assessment
        $this->redirectIfSubmittedOrFinished($this->assessmentId, $this->frameworkId);

        if (!empty($this->assessmentId) && !$this->back) {
            $node = $this->getAssessmentResumeNode($this->assessmentId);
            if (!empty($node)) {
                // There are answered questions, so we should resume there
                $this->redirect(route('questions', [
                    'assessmentId' => $this->assessmentId,
                    'nodeId' => $node?->id
                ]));
            }
        }

        $this->data = $this->variantSelections()?->toArray();
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        return $this->assessmentId
            ? Assessment::find($this->assessmentId)
            : null;
    }

    /**
     * Get the next or last node for the assessment
     * to navigate to if the user is resuming an assessment.
     *
     * @param int|null $assessmentId
     * @param bool $next
     * @return Node|null
     */
    public function getAssessmentResumeNode(?int $assessmentId = null, bool $next = true): ?Node
    {
        if ($next) {
            // Next unanswered node
            return Node::with('questions')
                ->whereHas('questions') // must have questions
                ->whereDoesntHave('questions.responses', function ($q) use ($assessmentId) {
                    $q->where('assessment_id', $assessmentId);
                })
                ->orderBy('order', 'asc')
                ->first();
        }

        // Last answered node
        return Node::with('questions')
            ->whereHas('questions.responses', function ($q) use ($assessmentId) {
                $q->where('assessment_id', $assessmentId);
            })
            ->orderBy('order', 'desc')
            ->first();
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
        if (empty($this->frameworkId) || !is_numeric($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->variantAttributes()->get();
    }


    #[Computed]
    public function frameworks(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Framework|null
    {
        return Framework::with(['variantAttributes.options'])->find($this->frameworkId);
    }

    #[Computed]
    public function variantSelections(): Collection|null
    {
        if (empty($this->assessmentId)) {
            return Collection::empty();
        }

        return $this->user()->assessments()->where('id', $this->assessmentId)?->first()
            ?->variantSelections->pluck('framework_variant_option_id', 'attribute.key');
    }

    public function store(): void
    {
        $attributes = $this->attributes()?->keyBy('key');
        if (!$this->validateVariants($attributes)) {
            return;
        }

        if (empty($this->assessmentId)) {
            $this->assessmentId = $this->initialiseAssessment();
        }
        if ($this->assessmentId) {
            $attributes = $this->attributes()?->keyBy('key');
            foreach ($this->data as $key => $value) {
                if (isset($attributes[$key])) {
                    UserAssessmentVariantSelectionService::updateOrCreate($value, $attributes[$key], $this->assessmentId);
                }
            }
            $this->redirect(
                route('questions',
                    [
                        'assessmentId' => $this->assessmentId,
                        'nodeId' => null
                    ]
                )
            );
        } else {
            $this->dispatch(
                'alert',
                type: 'error',
                message: __('alerts.errors.assessment-initialise'),
            );
        }
    }

    public function validateVariants($attributes)
    {
        $this->resetErrorBag();
        $input = $this->data;
        foreach ($attributes as $key => $attribute) {

            // Check if user submitted this key
            if (!array_key_exists($key, $input) || empty($input[$key])) {
                $this->addError('data.' . $key, "The {$attribute->label} field is required.");
            }
        }
        return $this->getErrorBag()->isEmpty();
    }

    public function initialiseAssessment()
    {
        try {
            DB::transaction(function () {
                // Create the assessment
                $assessment = Assessment::create([
                    'framework_id' => $this->frameworkId,
                    'user_id' => $this->user()->user_id,
                ]);
                $this->assessmentId = $assessment->id;

                // Ensure rater record exists (no duplicates)
                $rater = Rater::firstOrCreate(
                    ['user_id' => $this->user()?->user_id ?? null],
                    ['created_at' => now()] // optional defaults
                );

                // Link rater to this assessment (avoid duplicates too)
                AssessmentRater::firstOrCreate([
                    'assessment_id' => $assessment->id,
                    'rater_id' => $rater->id,
                ]);

            }, 3); // retry count for deadlocks.
            return $this->assessmentId ?? null;
        } catch (\Throwable $e) {
            report($e); // log the error for debugging
            $this->dispatch(
                'alert',
                type: 'error',
                message: __('alerts.errors.assessment-initialise'),
            );
            return false;
        }
    }

    public function goPrevious()
    {
        return redirect()->route('instructions', [
            'frameworkId' => $this->frameworkId,
            'assessmentId' => $this->assessmentId,
        ]);
    }

    public function render()
    {
        return view('livewire.variants');
    }
}
