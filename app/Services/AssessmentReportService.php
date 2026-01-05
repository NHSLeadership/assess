<?php

namespace App\Services;

use App\Models\Framework;
use App\Models\Node;
use App\Models\Assessment;
use App\Models\Rater;
use Illuminate\Support\Collection;

class AssessmentReportService
{
    public array $radarOptions = [];
    public array $radarData = [];
    public array $barCharts = [];

    public function __construct(
        public int $frameworkId,
        public int $assessmentId
    ) {}

    public function framework(): ?Framework
    {
        return Framework::find($this->frameworkId);
    }

    public function nodes(): Collection
    {
        return Node::where('framework_id', $this->frameworkId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }

    public function assessment(): ?Assessment
    {
        return Assessment::find($this->assessmentId);
    }

    public function responses(): ?Collection
    {
        return $this->assessment()?->responses()->get();
    }

    public function rater(): ?Rater
    {
        return Rater::where('user_id', auth()->id())
            ->whereHas('assessments', fn($q) => $q->where('assessments.id', $this->assessmentId))
            ->first();
    }

    /* ---------------------------------------------------------
       BAR CHARTS
    --------------------------------------------------------- */
    public function barCharts(): array
    {
        $areas = $this->nodes()->whereNull('parent_id');

        foreach ($areas as $area) {

            $standards = $this->nodes()->filter(fn($n) => $n->parent_id === $area->id);

            if ($standards->isEmpty()) {
                continue;
            }

            $labels = [];
            $values = [];

            foreach ($standards as $standard) {

                $leafNodes = $this->nodes()->filter(fn($n) =>
                    $n->children->count() === 0 &&
                    $n->parent_id === $standard->id
                );

                if ($leafNodes->isEmpty()) {
                    continue;
                }

                $scaleResponses = $this->responses()->filter(fn($r) =>
                    $leafNodes->pluck('id')->contains($r->question->node_id) &&
                    $r->question->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
                );

                if ($scaleResponses->isEmpty()) {
                    continue;
                }

                $avg = round(
                    $scaleResponses->avg(fn($r) => (int) ($r->scaleOption->value ?? 0))
                );

                $labels[] = $standard->name;
                $values[] = $avg;
            }

            if (empty($labels)) {
                continue;
            }

            $this->barCharts[] = [
                'node_id' => $area->id,
                'id' => 'barChart_' . $area->id,
                'title' => $area->name,
                'description' => $area->description ?? '',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Standards',
                        'data' => $values,
                        'backgroundColor' => $area?->colour ?? 'rgba(79,70,229,0.5)',
                        'borderColor' => $area?->colour ?? '#4F46E5',
                        'borderWidth' => 1,
                        'barThickness' => 50,
                    ]],
                ],
                'options' => [
                    'min' => 0,
                    'max' => 5,
                    'tickColor' => '#374151',
                    'gridColor' => 'rgba(0,0,0,0.1)',
                    'categoryPercentage' => 10,
                    'barPercentage' => 10,
                ],
            ];
        }

        return $this->barCharts;
    }

    /* ---------------------------------------------------------
       RADAR CHART
    --------------------------------------------------------- */
    public function radarChart(): array
    {
        $areas = $this->nodes()->whereNull('parent_id');

        $labels = [];
        $values = [];

        foreach ($areas as $area) {

            $standards = $this->nodes()->filter(fn($n) => $n->parent_id === $area->id);

            if ($standards->isEmpty()) {
                continue;
            }

            $standardAverages = [];

            foreach ($standards as $standard) {

                $leafNodes = $this->nodes()->filter(fn($n) =>
                    $n->children->count() === 0 &&
                    $n->parent_id === $standard->id
                );

                if ($leafNodes->isEmpty()) {
                    continue;
                }

                $scaleResponses = $this->responses()->filter(fn($r) =>
                    $leafNodes->pluck('id')->contains($r->question->node_id) &&
                    $r->question->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
                );

                if ($scaleResponses->isEmpty()) {
                    continue;
                }

                $avg = round(
                    $scaleResponses->avg(fn($r) => (int) ($r->scaleOption->value ?? 0)),
                    1
                );

                $standardAverages[] = $avg;
            }

            if (empty($standardAverages)) {
                continue;
            }

            $areaAvg = round(array_sum($standardAverages) / count($standardAverages), 1);

            $labels[] = $area->name;
            $values[] = $areaAvg;
        }

        $this->radarData = [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Area Overview',
                'data' => $values,
                'backgroundColor' => 'rgba(79,70,229,0.25)',
                'borderColor' => '#4F46E5',
                'pointBackgroundColor' => '#4F46E5',
                'borderWidth' => 2,
            ]]
        ];

        $this->radarOptions = [
            'min' => 0,
            'max' => 5,
            'tickColor' => '#374151',
            'gridColor' => 'rgba(0,0,0,0.1)',
            'angleLineColor' => 'rgba(0,0,0,0.2)',
        ];

        return [
            'data' => $this->radarData,
            'options' => $this->radarOptions,
        ];
    }
}
