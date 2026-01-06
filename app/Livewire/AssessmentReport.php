<?php

namespace App\Livewire;

use App\Exceptions\AssessmentFrameworkMismatchException;
use App\Exceptions\AssessmentNotFoundException;
use App\Exceptions\AssessmentNotSubmittedException;
use App\Exceptions\FrameworkNotFoundException;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Rater;
use App\Services\AssessmentReportService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AssessmentReport extends Component
{
    use AssessmentHelperTrait;
    use UserTrait;

    public ?int $frameworkId = null;
    public ?int $assessmentId = null;

    public array $barCharts = [];
    public array $radarOptions = [];
    public array $radarData = [];

    /**
     * @throws FrameworkNotFoundException
     * @throws AssessmentNotFoundException
     * @throws AssessmentFrameworkMismatchException
     * @throws AssessmentNotSubmittedException
     */
    public function mount(int $frameworkId, int $assessmentId): void
    {
        $this->frameworkId  = $frameworkId;
        $this->assessmentId = $assessmentId;

        // Validate framework
        if (!$this->framework()) {
            throw new FrameworkNotFoundException(__('alerts.errors.framework-not-found'));
        }

        // Validate assessment
        if (!$this->assessment()) {
            throw new AssessmentNotFoundException(__('alerts.errors.assessment-not-found'));
        }

        if ($this->assessment()->framework_id !== $this->framework()->id) {
            throw new AssessmentFrameworkMismatchException(
                assessmentId: $this->assessmentId,
                frameworkId: $this->frameworkId,
                message: __('alerts.errors.assessment-not-belong-to-framework')
            );
        }

        if (is_null($this->assessment()->submitted_at)) {
            throw new AssessmentNotSubmittedException(
                assessmentId: $this->assessmentId,
                message: __('alerts.errors.assessment-not-submitted')
            );
        }


        // Use the shared service for all report data
        $service = new AssessmentReportService($frameworkId, $assessmentId);

        $this->barCharts = $service->barCharts();

        $radar = $service->radarChart();
        $this->radarData    = $radar['data'];
        $this->radarOptions = $radar['options'];
    }

    #[Computed]
    public function framework(): ?Framework
    {
        if (empty($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function nodes(): ?Collection
    {
        return Node::where('framework_id', $this->frameworkId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return Assessment::find($this->assessmentI);
    }

    #[Computed]
    public function responses(): ?Collection
    {
        return $this->assessment()?->responses()->get();
    }

    #[Computed]
    public function rater(): ?Rater
    {
        if (empty($this->assessmentId) || empty($this->user()?->user_id)) {
            return null;
        }

        return Rater::where('user_id', $this->user()?->user_id)
            ->whereHas('assessments', function ($q) {
                $q->where('assessments.id', $this->assessmentId);
            })
            ->first();
    }

    public function render()
    {
        return view('livewire.assessment-report');
    }
}
