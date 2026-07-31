<?php

namespace App\Livewire;

use App\Exceptions\AssessmentFrameworkMismatchException;
use App\Exceptions\AssessmentNotFoundException;
use App\Exceptions\AssessmentNotSubmittedException;
use App\Exceptions\FrameworkNotFoundException;
use App\Models\Assessment;
use App\Models\AssessmentRater;
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
    public ?int $raterId = null;

    public array $barCharts = [];

    public array $barChartsCompetency = [];

    public array $radarOptions = [];

    public array $radarData = [];

    public ?string $variantAttributeLabel = null;

    /** @var array<string, mixed> */
    public array $signposts = [];

    protected ?AssessmentRater $cachedAssessmentRater = null;

    public Collection $raterFeedback;

    public bool $reportAvailable = true;

    public int $totalRaters = 0;

    public int $completedRaters = 0;

    /**
     * @throws FrameworkNotFoundException
     * @throws AssessmentNotFoundException
     * @throws AssessmentFrameworkMismatchException
     * @throws AssessmentNotSubmittedException
     */
    public function mount(int $frameworkId, int $assessmentId, ?int $raterId = null): void
    {
        if (request()->route()?->getName() === 'assessment-rater-report' && ! request()->hasValidSignature()) {
            abort(403);
        }
        if (! empty($this->raterId) && ! $this->assessmentRater()) {
            abort(404);
        }

        $this->frameworkId = $frameworkId;
        $this->assessmentId = $assessmentId;
        $this->raterId = $raterId;

        $this->raterFeedback = collect();

        // Validate framework
        if (!$this->framework() instanceof \App\Models\Framework) {
            throw new FrameworkNotFoundException(__('alerts.errors.framework-not-found'));
        }

        // Validate assessment
        if (!$this->assessment() instanceof \App\Models\Assessment) {
            throw new AssessmentNotFoundException(__('alerts.errors.assessment-not-found'));
        }

        if (empty($this->raterId) && ($this->assessment()->user_id !== $this->user()?->user_id)) {
            abort(404);
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

        if (!empty($this->raterId)) {
            $rater = $this->assessment()->raters()
                ->where('raters.id', $this->raterId)
                ->firstOrFail();

            if (is_null($rater->pivot->submitted_at)) {
                throw new AssessmentNotSubmittedException(
                    assessmentId: $this->assessmentId,
                    message: __('alerts.errors.assessment-rater-not-submitted')
                );
            }
        }

        $this->reportAvailable = $this->assessment()->is360Complete();

        $this->totalRaters = $this->assessment()
            ->raters()
            ->count();

        $this->completedRaters = $this->assessment()
            ->raters()
            ->wherePivotNotNull('submitted_at')
            ->count();

        $this->reportAvailable = $this->assessment()->is360Complete();

        $service = new AssessmentReportService(
            $frameworkId,
            $assessmentId,
            $this->raterId
        );

        $this->variantAttributeLabel = $service->variantAttributeLabel();

        if (! $this->reportAvailable) {
            return;
        }

        $this->raterFeedback = $service->raterFeedbackByStandard();

        $radar = $service->radarChart(
            hasRaters: $this->totalRaters > 0
        );

        $this->radarData = $radar['data'];
        $this->radarOptions = $radar['options'];

        $this->signposts = [];

        $this->barCharts = [];

        foreach ($service->nodes() as $node) {

            $chart = $service->barChart(
                $node,
                hasRaters: $this->totalRaters > 0
            );

            if ($chart) {
                $this->barCharts[$node->id] = $chart;
            }

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
        //return $this->assessment()?->responses()->get();
        $query = $this->assessment()?->responses();

        if ($this->raterId) {
            $query->where('rater_id', $this->raterId);
        }

        return $query?->get();
    }

    #[Computed]
    public function rater(): ?Rater
    {
        if ($this->assessmentId === null || $this->assessmentId === 0 || empty($this->user()->user_id)) {
            return null;
        }

        return Rater::where('subject_id', $this->user()->user_id)
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

    #[Computed]
    public function reportService(): AssessmentReportService
    {
        return new AssessmentReportService(
            $this->frameworkId,
            $this->assessmentId,
            $this->raterId
        );
    }
}
