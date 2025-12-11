<?php

namespace App\Livewire;

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\Node;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\RedirectSubmittedAssessment;
use App\Traits\UserTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class Assessments extends Component
{
    use FormFieldValidationRulesTrait;
    use WithPagination;
    use UserTrait;
    use RedirectSubmittedAssessment;

    public ?string $assessmentId;

    protected ?int $perPage = 1;
    protected ?string $pageName = 'assessmentId';
    protected ?bool $simplePagination = true;

    public Node|null $currentNode;

    public ?int $nodeId = null;

    public function mount($assessmentId)
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        //Redirect already submitted assignments to summary page
        $this->redirectIfSubmitted($this->assessmentId, $this->assessment?->framework?->id);
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
    public function nodes()
    {
        if (empty($this->assessment) || empty($this->assessment->framework)) {
            return null;
        }

        $selectedOptionIds = $this->assessment->variantSelections()
            ->pluck('framework_variant_option_id')
            ->all();

        if (empty($selectedOptionIds)) {
            return $this->assessment->framework->nodes()->whereRaw('0 = 1');
        }

        $requiredMatchCount = count($selectedOptionIds);

        $rater = $this->assessment->raters()->first();
        $subjectUserId = $this->assessment->getAttribute('user_id');
        $raterUserId = $rater?->getAttribute('user_id');
        $raterType = ($raterUserId && $subjectUserId && $raterUserId === $subjectUserId)
            ? RaterType::Self
            : RaterType::Rater;

        // return nodes that have at least one question with a variant that:
        // - matches rater_type (null or specific)
        // - has matches for every selected option id (and the matched count equals the selected count)
        return $this->assessment->framework->nodes()
            ->whereHas('questions', function ($q) use ($selectedOptionIds, $raterType, $requiredMatchCount) {
                $q->where('active', true)
                    ->whereHas('variants', function ($v) use ($selectedOptionIds, $raterType, $requiredMatchCount) {
                    $v->where(function ($w) use ($raterType) {
                        $w->whereNull('rater_type')->orWhere('rater_type', $raterType);
                    })
                        ->whereHas('matches', function ($m) use ($selectedOptionIds) {
                            $m->whereIn('framework_variant_option_id', $selectedOptionIds);
                        }, '=', $requiredMatchCount);
                });
            })
            ->orderBy('order');
    }

    #[Computed]
    public function data()
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return $this->user?->data?->where('assessment_id', $this->assessmentId)->get();
    }

    public function backPage()
    {
        $this->previousPage();
    }

    #[Computed]
    public function responses(): Collection
    {
        return $this->user->assessments()->where('id', $this->assessmentId)->get();
    }

    #[On('questions-next-node')]
    public function currentQuestionNode($nodeId = null)
    {
        $this->currentNode = Node::find($nodeId);
    }
    public function render()
    {
        return view('livewire.assessments', [
            'paginatedNodes' => $this->nodes()?->paginate($this->perPage, pageName: $this->pageName),
        ]);
    }
}
