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
use App\Services\FrameworkTraversalService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

class AssessmentReport extends Component
{
    use AssessmentHelperTrait;
    use UserTrait;

    public ?int $frameworkId = null;

    public ?int $assessmentId = null;

    public array $barCharts = [];

    public array $barChartsCompetency = [];

    public array $radarOptions = [];

    public array $radarData = [];

    public ?string $variantAttributeLabel = null;

    /** @var array<string, mixed> */
    public array $signposts;

    /**
     * @throws FrameworkNotFoundException
     * @throws AssessmentNotFoundException
     * @throws AssessmentFrameworkMismatchException
     * @throws AssessmentNotSubmittedException
     */
    public function mount(int $frameworkId, int $assessmentId): void
    {
        $this->frameworkId = $frameworkId;
        $this->assessmentId = $assessmentId;

        // Validate framework
        if (!$this->framework() instanceof \App\Models\Framework) {
            throw new FrameworkNotFoundException(__('alerts.errors.framework-not-found'));
        }

        // Validate assessment
        if (!$this->assessment() instanceof \App\Models\Assessment) {
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
        $this->barChartsCompetency = $service->barChartsCompetency();
        $radar = $service->radarChart();
        $this->radarData = $radar['data'];
        $this->radarOptions = $radar['options'];
        $this->variantAttributeLabel = $service->variantAttributeLabel();

        $this->signposts = [];

        foreach ($service->nodes() as $node) {
            $signposts = $service->signpostsForNode($node);
            if ($signposts !== []) {
                $this->signposts[$node->id] = $signposts;
            }
        }
    }

    #[Computed]
    public function framework(): ?Framework
    {
        if ($this->frameworkId === null || $this->frameworkId === 0) {
            return null;
        }

        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function nodes(): ?Collection
    {
        if ($this->frameworkId === null || $this->frameworkId === 0) {
            return null;
        }

        return app(FrameworkTraversalService::class)
            ->orderedHierarchyNodes((int) $this->frameworkId);
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        if ($this->assessmentId === null || $this->assessmentId === 0) {
            return null;
        }

        return Assessment::find($this->assessmentId);
    }

    #[Computed]
    public function responses(): ?Collection
    {
        return $this->assessment()?->responses()->get();
    }

    #[Computed]
    public function rater(): ?Rater
    {
        if ($this->assessmentId === null || $this->assessmentId === 0 || empty($this->user()->user_id)) {
            return null;
        }

        return Rater::where('user_id', $this->user()->user_id)
            ->whereHas('assessments', function ($q): void {
                $q->where('assessments.id', $this->assessmentId);
            })
            ->first();
    }

    #[Title('Assessment report')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.assessment-report');
    }
}
