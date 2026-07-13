<?php

namespace App\Livewire;

use App\Enums\RaterType;
use App\Models\AssessmentRater;
use App\Models\Rater;
use App\Models\RaterGroup;
use App\Traits\UserTrait;
use App\Traits\AssessmentHelperTrait;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class SelectRater extends Component
{
    use UserTrait;
    use AssessmentHelperTrait;

    public int $assessmentId;

    public ?int $selectedRaterId = null;
    public ?string $type = null;
    public ?int $groupId = null;

    public bool $showNewGroup = false;
    public ?string $newGroupName = null;

    public array $raterList = [];
    public array $raterTypeList = [];
    public array $raterGroupList = [];

    public function mount(int $assessmentId): void
    {
        $this->assessmentId = $assessmentId;

        if ($this->assessment?->user_id !== $this->user()?->user_id) {
            abort(404);
        }

        $this->refreshGroupList();
        $this->refreshRaterList();
        $this->raterTypeList = RaterType::options();
    }

    protected function rules(): array
    {
        return [
            'selectedRaterId' => ['required', 'integer'],
            'type' => ['required'],
            'groupId' => ['nullable', 'integer'],
        ];
    }

    protected function refreshRaterList(): void
    {
        $attachedRaterIds = AssessmentRater::query()
            ->where('assessment_id', $this->assessmentId)
            ->pluck('rater_id')
            ->toArray();

        $this->raterList = Rater::query()
                ->where('subject_id', $this->user()?->user_id)
                ->whereNotIn('id', $attachedRaterIds)
                ->where(
                    'email_hash',
                    '!=',
                    Rater::emailHash((string) $this->user()?->email)
                )
            ->with('assessmentRaters')
                ->get()
                ->mapWithKeys(function (Rater $rater) {


                    $type = $rater->assessmentRaters
                        ->first()?->type?->value;

                    return [
                        $rater->id => sprintf(
                            '%s (%s)%s',
                            $rater->name,
                            $rater->email,
                            $type ? ' - ' . ucfirst($type) : ''
                        ),
                    ];
                })
                ->toArray();

    }

    public function store(): void
    {
        try {
            $this->validate();
            $rater = Rater::query()
                ->whereKey($this->selectedRaterId)
                ->where('subject_id', $this->user()?->user_id)
                ->firstOrFail();
            AssessmentRater::create([
                'assessment_id' => $this->assessmentId,
                'rater_id' => $rater->id,
                'type' => $this->type,
                'rater_group_id' => $this->groupId,
            ]);

            session()->flash('success', [
                'heading' => __('Rater selected'),
                'message' => __('Rater selected successfully.'),
            ]);

            $this->redirectRoute('assessment-raters', [
                'assessmentId' => $this->assessmentId,
            ]);


        } catch (ValidationException $e) {
            throw $e;

        } catch (Throwable $e) {
            report($e);

            session()->flash(
                'error',
                'Unable to select the rater.'
            );
        }
    }

    public function render()
    {
        return view('livewire.select-rater')
            ->title(__('pages.raters.select-rater'));
    }
}
