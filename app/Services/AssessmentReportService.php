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
    )
    {
    }

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

                $leafNodes = $this->nodes()->filter(fn($n) => $n->children->count() === 0 &&
                    $n->parent_id === $standard->id
                );

                if ($leafNodes->isEmpty()) {
                    continue;
                }

                $scaleResponses = $this->responses()->filter(fn($r) => $leafNodes->pluck('id')->contains($r->question->node_id) &&
                    $r->question->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
                );

                if ($scaleResponses->isEmpty()) {
                    continue;
                }

                $avg = round(
                    $scaleResponses->avg(fn($r) => (int)($r->scaleOption->value ?? 0))
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
                        'label' => 'Self assessment',
                        'data' => $values,
                        'backgroundColor' => $area?->colour ?? 'rgba(79,70,229,0.5)',
                        'borderColor' => $area?->colour ?? '#4F46E5',
                        'borderWidth' => 1,
                        'barThickness' => 50,
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

        return $this->barCharts;
    }

    /* ---------------------------------------------------------
       RADAR CHART
    --------------------------------------------------------- */

    /**
     * Generate a radar chart with one data point per standard
     *
     * @param bool $useScaleLabels
     * @return array
     */
    public function radarChart(bool $useScaleLabels = true): array
    {
        // Ensure bar charts are generated
        if (empty($this->barCharts)) {
            $this->barCharts();
        }

        $labels = [];
        $values = [];

        $scaleOptions = \App\Models\ScaleOption::orderBy('value')->pluck('label')->toArray();
        $callback = $useScaleLabels
            ? "function(value, index, values) {
                const labels = " . json_encode($scaleOptions) . ";
                return labels[index] ?? value;
            }"
            : null;

        foreach ($this->barCharts as $chart) {
            // Each bar chart has multiple labels and values
            foreach ($chart['data']['labels'] as $i => $label) {
                $labels[] = $label;
                $values[] = $chart['data']['datasets'][0]['data'][$i];
            }
        }
        return [
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Self assessment',
                    'data' => $values,
                    'backgroundColor' => 'rgba(79,70,229,0.25)',
                    'borderColor' => '#4F46E5',
                    'pointBackgroundColor' => '#4F46E5',
                    'borderWidth' => 2,
                ]]
            ],
            'options' => [
                'min' => 1,
                'max' => 5,
                'tickColor' => '#212b32',
                'pointLabelsColor' => '#212b32',
                'legendLabelsColor' => '#212b32',
                'callback' => $callback
            ]
        ];
    }

    private function descendantLeafNodes(Node $node): \Illuminate\Support\Collection
    {
        $all = $this->nodes();
        // build map: parent_id => [nodes]
        $childrenMap = [];
        foreach ($all as $n) {
            $childrenMap[$n->parent_id ?? 0][] = $n;
        }

        $stack = [$node];
        $leaves = collect();

        while (!empty($stack)) {
            /** @var Node $current */
            $current = array_pop($stack);
            $children = $childrenMap[$current->id] ?? [];

            if (empty($children)) {
                $leaves->push($current);
            } else {
                foreach ($children as $child) {
                    $stack[] = $child;
                }
            }
        }

        return $leaves;
    }

    public function averageForNode(Node $node): ?float
    {
        $leafNodes = $this->descendantLeafNodes($node);

        if ($leafNodes->isEmpty()) {
            return null;
        }

        $leafIds = $leafNodes->pluck('id')->toArray();

        $scaleResponses = $this->responses()->filter(fn($r) =>
            in_array($r->question->node_id, $leafIds, true) &&
            $r->question->response_type === \App\Enums\ResponseType::TYPE_SCALE->value
        );

        if ($scaleResponses->isEmpty()) {
            return null;
        }

        return round(
            $scaleResponses->avg(fn($r) => (int)($r->scaleOption->value ?? 0)),
            1
        );
    }

    public function signpostsForNode(Node $node): array
    {
        $avg = $this->averageForNode($node);
        if ($avg === null) {
            return [];
        }

        $selectedOptionIds = $this->assessment()?->variantSelections()
            ? $this->assessment()->variantSelections()->pluck('framework_variant_option_id')->toArray()
            : [];

        $query = \App\Models\Signpost::query()
            ->where('node_id', $node->id)
            ->where('min_value', '<=', $avg)
            ->where('max_value', '>=', $avg)
            ->orderBy('min_value')
            ->orderBy('max_value');

        if (!empty($selectedOptionIds)) {
            $query->where(function ($q) use ($selectedOptionIds) {
                $q->whereNull('framework_variant_option_id')
                    ->orWhereIn('framework_variant_option_id', $selectedOptionIds);
            });
        } else {
            $query->whereNull('framework_variant_option_id');
        }

        // return array of Signpost models for this specific node
        return $query->get()->all();
    }
}
