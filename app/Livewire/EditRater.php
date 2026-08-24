<?php

namespace App\Livewire;

use App\Enums\RaterType;
use App\Models\AssessmentRater;
use App\Models\Rater;
use App\Services\RaterInvitationService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class EditRater extends Component
{
    use AssessmentHelperTrait;
    use UserTrait;

    public ?int $assessmentId = null;
    public ?int $assessmentRaterId = null;

    public ?int $raterId = null;
    public ?int $groupId = null;

    public ?string $name = null;
    public ?string $email = null;
    public ?string $type = null;
    public bool $showNewRater = true;
    public ?int $selectedRaterId = null;
    public array $existingRaterList = [];
    public array $raterTypeList = [];
    public array $raterGroupList = [];
    public ?string $source = null;

    public function mount(
        ?int $assessmentId = null,
        ?int $assessmentRaterId = null
    ): void
    {
        //@TODO Remove abort statement once 360 is live
        abort_unless(
            app()->runningUnitTests()
            || $this->user()->can('assess:360'),
            404
        );

        $this->assessmentId = $assessmentId;
        $this->assessmentRaterId = $assessmentRaterId;

        if (! empty($this->assessmentRaterId)) {
            $assessmentRater = AssessmentRater::findOrFail($this->assessmentRaterId);

            if ($assessmentRater->assessment?->user_id !== $this->user()?->user_id) {
                abort(404);
            }

            $this->assessmentId = $assessmentRater->assessment_id;
            $this->raterId = $assessmentRater->rater_id;

            $this->name = $assessmentRater->rater?->name;
            $this->email = $assessmentRater->rater?->email;

            $this->type = $assessmentRater->type->value;
            $this->groupId = $assessmentRater->rater_group_id;
        }
        if ($this->assessment?->user_id !== $this->user()?->user_id) {
            abort(404);
        }

        $this->raterTypeList = RaterType::options();

        $this->refreshGroupList();
        $this->refreshRaterList();
        $this->source = request('source');
    }

    protected function existingRaterRules(): array
    {
        return array_merge(
            $this->commonRules(),
            [
                'selectedRaterId' => ['required', 'integer'],
            ]
        );

    }

    protected function raterRules(): array
    {
        return array_merge(
            $this->commonRules(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email:rfc'],
            ]
        );

    }

    protected function commonRules(): array
    {
        return [
            'type' => ['required'],
            'groupId' => [
                'nullable',
                'integer',
                Rule::exists('rater_groups', 'id')->where(
                    fn ($query) => $query->where(
                        'subject_id',
                        $this->user()?->user_id
                    )
                ),

            ],
        ];
    }


    protected function messages(): array
    {
        return [
            'newGroupName.unique' => 'A group with this name already exists.',
        ];
    }



    protected function findExistingRater(): ?Rater
    {
        return Rater::query()
            ->where('subject_id', $this->user()?->user_id)
            ->where('email_hash', Rater::emailHash((string) $this->email))
            ->first();
    }

    protected function validateRaterDoesNotAlreadyExist(): ?Rater
    {
        $rater = $this->findExistingRater();

        if (! $rater) {
            return null;
        }

        $existingAssessmentRater = AssessmentRater::query()
            ->where('assessment_id', $this->assessmentId)
            ->where('rater_id', $rater->id)
            ->first();

        if (
            $existingAssessmentRater &&
            $existingAssessmentRater->id !== $this->assessmentRaterId
        ) {
            throw ValidationException::withMessages([
                'email' => 'This rater has already been added to the assessment.',
            ]);
        }

        return $rater;
    }

    public function store(): void
    {
        try {

            if ($this->showNewRater) {

                $this->validate($this->raterRules());

                $rater = $this->validateRaterDoesNotAlreadyExist();
                if (! $rater) {
                    $rater = Rater::create([
                        'subject_id' => $this->user()?->user_id,
                        'name' => trim((string) $this->name),
                        'email' => trim((string) $this->email),
                    ]);
                } else {
                    $rater->update([
                        'name' => trim((string) $this->name),
                    ]);
                }

            } else {

                $this->validate($this->existingRaterRules());

                $rater = Rater::query()
                    ->whereKey($this->selectedRaterId)
                    ->where('subject_id', $this->user()?->user_id)
                    ->firstOrFail();
            }

            if ($this->assessmentRaterId) {

                $assessmentRater = AssessmentRater::findOrFail(
                    $this->assessmentRaterId
                );

                if ($this->showNewRater) {
                    $rater->update([
                        'name' => trim((string) $this->name),
                        'email' => trim((string) $this->email),
                    ]);
                }

                $assessmentRater->update([
                    'rater_id' => $rater->id,
                    'type' => $this->type,
                    'rater_group_id' => $this->groupId,
                ]);

                session()->flash('success', [
                    'heading' => __('Rater updated'),
                    'message' => __('Rater updated successfully.'),
                ]);

            } else {

                AssessmentRater::create([
                    'assessment_id' => $this->assessmentId,
                    'rater_id' => $rater->id,
                    'type' => $this->type,
                    'rater_group_id' => $this->groupId,
                ]);

                app(RaterInvitationService::class)->send($this->assessment, $rater);

                session()->flash('success', [
                    'heading' => __('Rater invited'),
                    'message' => __('messages.rater_invited', [
                        'email' => $rater->email,
                    ]),
                ]);
            }

            $this->redirect(
                route('assessment-raters',
                    [
                        'assessmentId' => $this->assessmentId,
                        'source' => $this->source,
                    ]
                )
            );

        } catch (ValidationException $e) {
            throw $e;

        } catch (Throwable $e) {
            report($e);

            session()->flash(
                'error',
                'Unable to save the rater. Please try again.'
            );
            $this->dispatch('scroll-to-top');
        }
    }

    public function isEditMode(): bool
    {
        return ! empty($this->assessmentRaterId);
    }

    protected function refreshRaterList(): void
    {
        $attachedRaterIds = [];

        if ($this->assessmentId) {
            $attachedRaterIds = AssessmentRater::query()
                ->where('assessment_id', $this->assessmentId)
                ->pluck('rater_id')
                ->toArray();
        }

        // During edit, allow the currently attached rater
        if ($this->raterId) {
            $attachedRaterIds = array_diff(
                $attachedRaterIds,
                [$this->raterId]
            );
        }

        $this->existingRaterList = Rater::query()
            ->where('subject_id', $this->user()?->user_id)
            ->whereNotIn('id', $attachedRaterIds)
            ->get()
            ->mapWithKeys(fn (Rater $rater) => [
                $rater->id => $rater->name . ' (' . $rater->email . ')',
            ])
            ->toArray();
    }

    public function useSelectedRater(): void
    {
        if (empty($this->selectedRaterId)) {
            return;
        }

        $rater = Rater::findOrFail($this->selectedRaterId);

        $this->raterId = $rater->id;
        $this->name = $rater->name;
        $this->email = $rater->email;

        $this->showNewRater = false;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.edit-rater')
            ->title(__('pages.raters.title'));
    }
}
