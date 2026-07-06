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

    public function mount($assessmentId): void
    {
        $this->assessmentId = $assessmentId;
        if ($this->assessment?->user_id !== $this->user()?->user_id) {
            abort(404);
        }
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
                $assessmentRater->delete();
                session()->flash('success', [
                    'heading' => __('Rater detached'),
                    'message' => __('Rater detached successfully.'),
                ]);
            } else {
                session()->flash('error', __('Failed to detach rater. Please try again.'));
            }

        } catch (Throwable $e) {
            Log::error('Error detaching rater', [
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

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.assessment-raters')
            ->title(__('pages.raters.title'));
    }

}
