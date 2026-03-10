<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Rater;
use Illuminate\Support\Collection;

class AssessmentReportService
{
    public array $barCharts = [];
    public string $chartBackgroundColor = '#ccdff1';
    public string $chartBorderColor = '#004281';

    private Assessment $assessment;
    private Collection $nodes;
    private ?Rater $rater;

    private FrameworkTraversalService $traversal;

    public function __construct(
        public int $frameworkId,
        public int $assessmentId
    ) {
        $this->traversal = app(FrameworkTraversalService::class);

        $this->assessment = Assessment::with([
            'responses.question.node',
            'responses.scaleOption',
            'framework.variantAttributes.options',
            'variantSelections.option',
        ])->findOrFail($assessmentId);

        $this->nodes = $this->traversal->orderedNodes(
            frameworkId: $frameworkId,
            depth: config('app.framework_node_depth'),
            withQuestions: true,
                activeOnly: true
        );

        // Prefer Academy ID style user_id if present
        $user = auth()->user();
        $userId = $user?->user_id ?? $user?->id;

        $this->rater = Rater::with('assessments')
            ->where('user_id', $userId)
            ->whereHas('assessments', fn ($q) => $q->where('assessments.id', $assessmentId))
            ->first();
    }

    public function framework(): ?Framework
    {
        return Framework::find($this->frameworkId);
    }

    public function nodes(): Collection
    {
        return $this->nodes;
    }

    public function assessment(): Assessment
    {
        return $this->assessment;
    }

    public function responses(): Collection
    {
        return $this->assessment->responses;
    }

    public function rater(): ?Rater
    {
        return $this->rater;
    }

    public function scaleOptions(): array
    {
        return \App\Models\ScaleOption::orderBy('value')->pluck('label', 'value')->toArray();
    }

    public function variantAttributeLabel(): ?string
    {
        // Assumes one variant selection per assessment
        return $this->assessment->variantSelections?->first()?->option?->label;
    }

    /* ---------------------------------------------------------
       BAR CHARTS (standard-level averages per area)
    --------------------------------------------------------- */
    public function barCharts(): array
    {
        $areas = $this->nodes()
            ->filter(fn (Node $n) => $n->parent_id === null)
            ->values();

        foreach ($areas as $area) {
            $standards = $area->children ?? collect();

            if ($standards->isEmpty()) {
                continue;
            }

            $labels = [];
            $values = [];

            foreach ($standards as $standard) {
                $leafNodes = $this->descendantLeafNodes($standard);

                if ($leafNodes->isEmpty()) {
                    continue;
                }

                $leafIds = $leafNodes->pluck('id')->all();

                $scaleResponses = $this->responses()->filter(fn ($r) =>
                    in_array($r->question?->node_id, $leafIds, true) &&
                    $r->question?->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
                );

                if ($scaleResponses->isEmpty()) {
                    continue;
                }

                $avg = round(
                    $scaleResponses->avg(fn ($r) => (int) ($r->scaleOption?->value ?? 0)),
                    2
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
                'scaleOptions' => $this->scaleOptions(),
                'title' => $area->name,
                'description' => $area->description ?? '',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Self assessment',
                        'data' => $values,
                        'backgroundColor' => $this->chartBackgroundColor,
                        'borderColor' => $this->chartBorderColor,
                        'borderWidth' => 1,
                    ]],
                ],
                'options' => [
                    'min' => 1,
                    'max' => 5,
                    'tickColor' => '#212b32',
                    'legendLabelsColor' => '#212b32',
                    'gridColor' => 'rgba(0,0,0,0.1)',
                ],
            ];
        }

        return $this->barCharts;
    }

    /* ---------------------------------------------------------
       BAR CHART - COMPETENCY LEVELS (leaf scores per area)
    --------------------------------------------------------- */
    public function barChartsCompetency(): array
    {
        $areas = $this->nodes()
            ->filter(fn (Node $n) => $n->parent_id === null)
            ->values();

        $charts = [];

        foreach ($areas as $area) {
            $standards = $area->children ?? collect();

            if ($standards->isEmpty()) {
                continue;
            }

            $labels = [];
            $values = [];

            foreach ($standards as $standard) {
                $leafNodes = $this->descendantLeafNodes($standard);

                foreach ($leafNodes as $leaf) {
                    $response = $this->responses()->first(fn ($r) =>
                        $r->question?->node_id === $leaf->id &&
                        $r->question?->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
                    );

                    if (! $response) {
                        continue;
                    }

                    $labels[] = $leaf->name;
                    $values[] = (int) ($response->scaleOption?->value ?? 0);
                }
            }

            if (empty($labels)) {
                continue;
            }

            $charts[] = [
                'node_id' => $area->id,
                'id' => 'barChartCompetency_' . $area->id,
                'scaleOptions' => $this->scaleOptions(),
                'title' => $area->name,
                'description' => $area->description ?? '',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Self assessment',
                        'data' => $values,
                        'backgroundColor' => $this->chartBackgroundColor,
                        'borderColor' => $this->chartBorderColor,
                        'borderWidth' => 1,
                    ]],
                ],
                'options' => [
                    'min' => 1,
                    'max' => 5,
                    'tickColor' => '#212b32',
                    'legendLabelsColor' => '#212b32',
                    'gridColor' => 'rgba(0,0,0,0.1)',
                    'categoryPercentage' => 10,
                    'barPercentage' => 10,
                ],
            ];
        }

        return $charts;
    }

    /* ---------------------------------------------------------
       RADAR CHART
    --------------------------------------------------------- */
    public function radarChart(bool $useScaleLabels = true): array
    {
        if (empty($this->barCharts)) {
            $this->barCharts();
        }

        $labels = [];
        $values = [];

        $scaleOptions = $this->scaleOptions();
        $scaleOptionsModified = array_values($scaleOptions);

        $callback = $useScaleLabels
            ? "function(value, index, values) {
                const labels = " . json_encode($scaleOptionsModified) . ";
                return labels[index] ?? value;
            }"
            : null;

        foreach ($this->barCharts as $chart) {
            foreach ($chart['data']['labels'] as $i => $label) {
                $labels[] = $this->wrapLabel($label);
                $values[] = $chart['data']['datasets'][0]['data'][$i];
            }
        }

        return [
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Self assessment',
                    'data' => $values,
                    'backgroundColor' => $this->chartBackgroundColor,
                    'borderColor' => $this->chartBorderColor,
                    'pointBackgroundColor' => '#4F46E5',
                    'borderWidth' => 2,
                ]],
            ],
            'options' => [
                'min' => 1,
                'max' => 5,
                'tickColor' => '#212b32',
                'pointLabelsColor' => '#212b32',
                'legendLabelsColor' => '#212b32',
                'callback' => $callback,
                'tickLabels' => $scaleOptions,
            ],
        ];
    }

    public function wrapLabel(string $label, int $maxLength = 12): array
    {
        $words = explode(' ', $label);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            if (strlen($current . $word) > $maxLength) {
                $lines[] = trim($current);
                $current = '';
            }
            $current .= $word . ' ';
        }

        if (trim($current) !== '') {
            $lines[] = trim($current);
        }

        return $lines;
    }

    private function descendantLeafNodes(Node $node): Collection
    {
        $leaves = collect();

        $walk = function (Node $current) use (&$walk, &$leaves) {
            $children = $current->children ?? collect();

            if ($children->isEmpty()) {
                $leaves->push($current);
                return;
            }

            foreach ($children as $child) {
                $walk($child);
            }
        };

        $walk($node);

        return $leaves;
    }

    public function averageForNode(Node $node): ?float
    {
        $leafNodes = $this->descendantLeafNodes($node);

        if ($leafNodes->isEmpty()) {
            return null;
        }

        $leafIds = $leafNodes->pluck('id')->all();

        $scaleResponses = $this->responses()->filter(fn ($r) =>
            in_array($r->question?->node_id, $leafIds, true) &&
            $r->question?->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
        );

        if ($scaleResponses->isEmpty()) {
            return null;
        }

        return round(
            $scaleResponses->avg(fn ($r) => (int) ($r->scaleOption?->value ?? 0)),
            1
        );
    }

    public function signpostsForNode(Node $node): array
    {
        $avg = $this->averageForNode($node);

        if ($avg === null) {
            return [];
        }

        $selectedOptionIds = $this->assessment->variantSelections()
            ? $this->assessment->variantSelections()->pluck('framework_variant_option_id')->toArray()
            : [];

        $query = \App\Models\Signpost::query()
            ->where('node_id', $node->id)
            ->where('min_value', '<=', $avg)
            ->where('max_value', '>=', $avg)
            ->orderBy('min_value')
            ->orderBy('max_value');

        if (! empty($selectedOptionIds)) {
            $query->where(function ($q) use ($selectedOptionIds) {
                $q->whereNull('framework_variant_option_id')
                    ->orWhereIn('framework_variant_option_id', $selectedOptionIds);
            });
        } else {
            $query->whereNull('framework_variant_option_id');
        }

        return $query->get()->all();
    }
}
