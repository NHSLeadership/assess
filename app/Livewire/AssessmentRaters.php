<?php

namespace App\Livewire;

use App\Models\AssessmentRater;
use App\Models\Rater;
use App\Services\RaterInvitationService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Throwable;

class AssessmentRaters extends Component
{
    use AssessmentHelperTrait;
    use UserTrait;

    public ?int $assessmentId = null;
    public ?int $pendingDetachId = null;
    public ?string $source = null;

    public function mount($assessmentId): void
    {
        //@TODO Remove abort statement once 360 is live
        abort_unless(
            app()->runningUnitTests()
            || $this->user()->can('assess:360'),
            404
        );

        $this->assessmentId = $assessmentId;
        if ($this->assessment?->user_id !== $this->user()?->user_id) {
            abort(404);
        }
        $this->source = request('source');
    }

    public function askDetach(int $id): void
    {
        $this->pendingDetachId = $id;
    }

    public function cancelDetach(): void
    {
        $this->pendingDetachId = null;
    }

    public function confirmDetach(): void
    {
        $id = $this->pendingDetachId;
        if (! $id) {
            return;
        }

        try {
            $assessmentRater = AssessmentRater::findOrFail($id);
            if($assessmentRater?->assessment?->user_id === $this->user()?->user_id) {
                $rater = $assessmentRater->rater;

                $assessmentRater->delete();

                if (
                    $rater &&
                    ! AssessmentRater::query()
                        ->where('rater_id', $rater->id)
                        ->exists()
                ) {
                    $rater->delete();
                }

                session()->flash('success', [
                    'heading' => __('Rater removed'),
                    'message' => __('Rater removed successfully.'),
                ]);
            } else {
                session()->flash('error', __('Failed to detach rater. Please try again.'));
            }

        } catch (Throwable $e) {
            Log::error('Error removing rater', [
                'assessment_id' => $this->assessmentId,
                'assessment_rater_id' => $id,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            session()->flash('error', __('Failed to detach rater. Please try again.'));
        } finally {
            $this->pendingDetachId = null;
        }
    }

    public function addNewRater()
    {
        $this->redirect(route('create-rater', [
            'assessmentId' => $this->assessmentId,
            'source' => $this->source,
        ]));
    }

    public function selectExistingRater()
    {
        $this->redirect(route('select-rater', [
            'assessmentId' => $this->assessmentId,
        ]));
    }

    public function editAssessmentRater($id): void
    {
        $this->redirect(route('edit-rater', [
            'assessmentRaterId' => $id,
            'source' => $this->source,
        ]));
    }

    public function inviteRater(int $raterId): void
    {
        try {
            $rater = Rater::findOrFail($raterId);

            app(RaterInvitationService::class)
                ->send($this->assessment, $rater);

            session()->flash('success', [
                'heading' => __('Invitation sent'),
                'message' => __('Invitation sent successfully.'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error sending rater invitation', [
                'assessment_id' => $this->assessmentId,
                'rater_id' => $raterId,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            session()->flash('error', [
                'heading' => __('Invitation failed'),
                'message' => __('Unable to send the invitation. Please try again.'),
            ]);
        }
    }

    public function goToQuestions(): void
    {
        $this->redirect(
            route('questions',
                [
                    'assessmentId' => $this->assessmentId,
                    'nodeId' => null,
                ]
            )
        );
    }

    public function goToVariantSelection(): void
    {
        $this->redirect(
            route(
                'variants',
                [
                    'frameworkId' => $this->assessment()?->framework->id,
                    'assessmentId' => $this->assessmentId,
                    'back' => 1,
                ]
            )
        );
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.assessment-raters')
            ->title(__('pages.raters.title'));
    }

}
