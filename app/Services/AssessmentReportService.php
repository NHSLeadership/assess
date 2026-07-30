<?php

namespace App\Services;

use App\Enums\Audience;
use App\Enums\RaterType;
use App\Enums\ResponseType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Question;
use App\Models\Rater;
use App\Models\ScaleOption;
use App\Models\Signpost;
use Illuminate\Support\Collection;

class AssessmentReportService
{
    public array $barCharts = [];

    public string $chartBackgroundColor = '#ccdff1';

    public string $chartBorderColor = '#004281';

    private readonly ?Assessment $assessment;

    private readonly ?Collection $nodes;

    private readonly ?Rater $rater;

    private const MIN_GROUP_SIZE = 3;

    public function __construct(
        public int $frameworkId,
        public int $assessmentId,
        public ?int $raterId = null
    ) {

        $this->assessment = Assessment::with([
            'responses.question.node',
            'responses.scaleOption',
            'framework.variantAttributes.options',
        ])->findOrFail($assessmentId);

        $this->nodes = app(FrameworkTraversalService::class)
            ->orderedHierarchyNodes($frameworkId);

        $this->rater = Rater::with('assessments')
            ->where('subject_id', auth()->id())
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

    public function assessment(): ?Assessment
    {
        return $this->assessment;
    }

    public function responses(): ?Collection
    {
        return $this->assessment->responses
            ->when(
                $this->raterId,
                fn ($responses) => $responses->where('rater_id', $this->raterId)
            );
    }

    public function scaleOptions(): array
    {
        return ScaleOption::orderBy('value')->pluck('label', 'value')->toArray();
    }

    public function rater(): ?Rater
    {
        return $this->rater;
    }

    public function variantAttributeLabel()
    {
        // @TODO: this currently assumes only one variant selection per assessment (stage)
        return $this->assessment()->variantSelections->first()->option->label ?? null;
    }

    /* ---------------------------------------------------------
       BAR CHART
    --------------------------------------------------------- */
    public function barChart(Node $node): ?array
    {
        $children = $this->nodes()
            ->where('parent_id', $node->id);

        if ($children->isEmpty()) {
            return null;
        }

        $include360 = Question::query()
            ->whereIn('node_id', $children->pluck('id'))
            ->whereHas('variants', function ($query): void {
                $query->where('audience', Audience::Rater);
            })
            ->exists();

        $labels = [];
        $selfValues = [];
        $raterValues = [];

        foreach ($children as $child) {

            $labels[] = $child->name;

            $selfValues[] = $this->averageForNode($child);

            if ($include360) {
                $raterValues[] = $this->averageRaterScoreForStandard($child);
            }
        }

        $datasets = [[
            'label' => 'Self assessment',
            'data' => $selfValues,
            'backgroundColor' => $this->chartBackgroundColor,
            'borderColor' => $this->chartBorderColor,
            'borderWidth' => 1,
        ]];

        if ($include360) {
            $datasets[] = [
                'label' => '360 feedback',
                'data' => $raterValues,
                'backgroundColor' => '#cce5d8',
                'borderColor' => '#00853F',
                'borderWidth' => 1,
            ];
        }

        return [
            'node_id' => $node->id,
            'id' => 'barChart_'.$node->id,
            'scaleOptions' => $this->scaleOptions(),
            'title' => $node->name,
            'description' => $node->description ?? '',
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
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

    /* ---------------------------------------------------------
       RADAR CHART
    --------------------------------------------------------- */

    /**
     * Generate a radar chart with one data point per standard
     */
    public function radarChart(
        bool $useScaleLabels = true,
        bool $hasRaters = false
    ): array {
        $labels = [];

        $selfValues = [];
        $raterValues = [];

        $scaleOptions = $this->scaleOptions();
        $scaleOptionsModified = array_values($scaleOptions);

        foreach (
            $this->nodes()
                ->whereNotNull('parent_id')
                ->filter(fn ($n) => $n->children->count() > 0)
            as $standard
        ) {
            $labels[] = $this->wrapLabel($standard->name);

            $selfValues[] = $this->averageForNode($standard);

            if ($hasRaters) {
                $raterValues[] = $this->averageRaterScoreForStandard($standard);
            }
        }

        $datasets = [
            [
                'label' => 'Self assessment',
                'data' => $selfValues,
                'backgroundColor' => $this->chartBackgroundColor,
                'borderColor' => $this->chartBorderColor,
                'pointBackgroundColor' => '#4F46E5',
                'borderWidth' => 2,
            ],
        ];

        if ($hasRaters) {
            $datasets[] = [
                'label' => '360 feedback',
                'data' => $raterValues,
                'backgroundColor' => 'rgba(0,133,63,0.2)',
                'borderColor' => '#00853F',
                'pointBackgroundColor' => '#00853F',
                'borderWidth' => 2,
            ];
        }

        return [
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
            'options' => [
                'min' => 1,
                'max' => 5,
                'tickColor' => '#212b32',
                'pointLabelsColor' => '#212b32',
                'legendLabelsColor' => '#212b32',
                'useScaleLabels' => $useScaleLabels,
                'tickLabels' => $scaleOptionsModified,

                'plugins' => [
                    'legend' => [
                        'display' => $hasRaters,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function wrapLabel($label, $maxLength = 12): array
    {
        $words = explode(' ', (string) $label);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            if (strlen($current.$word) > $maxLength) {
                $lines[] = trim($current);
                $current = '';
            }
            $current .= $word.' ';
        }

        if (trim($current) !== '') {
            $lines[] = trim($current);
        }

        return $lines;
    }

    private function descendantLeafNodes(Node $node): Collection
    {
        $all = $this->nodes();
        // build map: parent_id => [nodes]
        $childrenMap = [];
        foreach ($all as $n) {
            $childrenMap[$n->parent_id ?? 0][] = $n;
        }

        $stack = [$node];
        $leaves = collect();

        while ($stack !== []) {
            /** @var Node $current */
            $current = array_pop($stack);
            $children = $childrenMap[$current->id] ?? [];

            if ($children === []) {
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

        $scaleResponses = $this->responses()->filter(fn ($r): bool => in_array($r->question->node_id, $leafIds, true) &&
            $r->question->response_type === ResponseType::TYPE_SCALE->value
        );

        if ($scaleResponses->isEmpty()) {
            return null;
        }

        return round(
            $scaleResponses->avg(fn ($r): int => (int) ($r->scaleOption->value ?? 0)),
            1
        );
    }

    public function signpostsForNode(Node $node): array
    {
        $avg = $this->averageForNode($node);
        if ($avg === null) {
            return [];
        }

        $selectedOptionIds = $this->assessment()?->variantSelections() instanceof \Illuminate\Database\Eloquent\Relations\HasMany
            ? $this->assessment()->variantSelections()->pluck('framework_variant_option_id')->toArray()
            : [];

        $query = Signpost::query()
            ->where('node_id', $node->id)
            ->where('min_value', '<=', $avg)
            ->where('max_value', '>=', $avg)
            ->orderBy('min_value')
            ->orderBy('max_value');

        if (! empty($selectedOptionIds)) {
            $query->where(function ($q) use ($selectedOptionIds): void {
                $q->whereNull('framework_variant_option_id')
                    ->orWhereIn('framework_variant_option_id', $selectedOptionIds);
            });
        } else {
            $query->whereNull('framework_variant_option_id');
        }

        // return array of Signpost models for this specific node
        return $query->get()->all();
    }

    public function raterFeedbackByStandard(): Collection
    {
        $assessmentRaters = AssessmentRater::query()
            ->where('assessment_id', $this->assessmentId)
            ->with('group')
            ->get()
            ->keyBy('rater_id');

        $responses = $this->assessment()
            ->responses
            ->whereNotNull('rater_id')
            ->loadMissing([
                'question.node',
                'scaleOption',
                'rater',
            ]);

        return $responses
            ->groupBy('question.node_id')
            ->map(function (Collection $standardResponses) use ($assessmentRaters) {

                $scoresByType = $standardResponses
                    ->filter(fn ($r) => $r->scaleOption)
                    ->groupBy(function ($response) use ($assessmentRaters) {

                        return $assessmentRaters
                            ->get($response->rater_id)
                            ?->type
                            ?->value
                            ?? RaterType::Other->value;
                    })
                    ->map(function (Collection $responses) {

                        return [
                            'rater_count' => $responses
                                ->pluck('rater_id')
                                ->unique()
                                ->count(),

                            'average' => round(
                                $responses->avg(
                                    fn ($response) => $response->scaleOption?->value
                                ),
                                1
                            ),
                        ];
                    });

                $groupsByType = $standardResponses
                    ->filter(fn ($r) => $r->scaleOption)
                    ->groupBy(function ($response) use ($assessmentRaters) {

                        return $assessmentRaters
                            ->get($response->rater_id)
                            ?->type
                            ?->value
                            ?? RaterType::Other->value;
                    })
                    ->map(function (Collection $typeResponses) use ($assessmentRaters) {

                        return $typeResponses
                            ->groupBy(function ($response) use ($assessmentRaters) {

                                return $assessmentRaters
                                    ->get($response->rater_id)
                                    ?->group
                                    ?->name;
                            })
                            ->filter()
                            ->filter(function (Collection $groupResponses) {

                                return $groupResponses
                                        ->pluck('rater_id')
                                        ->unique()
                                        ->count() >= self::MIN_GROUP_SIZE;
                            })
                            ->map(function (Collection $groupResponses) {

                                return [
                                    'rater_count' => $groupResponses
                                        ->pluck('rater_id')
                                        ->unique()
                                        ->count(),

                                    'average' => round(
                                        $groupResponses->avg(
                                            fn ($response) => $response->scaleOption?->value
                                        ),
                                        1
                                    ),
                                ];
                            });
                    });

                $comments = $standardResponses
                    ->pluck('textarea')
                    ->filter()
                    ->shuffle()
                    ->values();

                return [
                    'scores_by_type' => $scoresByType,
                    'groups_by_type' => $groupsByType,
                    'comments' => $comments,
                ];
            });
    }

    public function averageRaterScoreForStandard(Node $standard): ?float
    {
        $responses = $this->assessment()
            ->responses
            ->whereNotNull('rater_id')
            ->filter(fn ($response) =>
                $response->question?->node_id === $standard->id
            );

        if ($responses->isEmpty()) {
            return null;
        }

        return round(
            $responses->avg(
                fn ($response) => (int) ($response->scaleOption?->value ?? 0)
            ),
            1
        );
    }

    private function childNodesHaveRaterQuestions(Node $node): bool
    {
        $childIds = $this->nodes()
            ->where('parent_id', $node->id)
            ->pluck('id');

        if ($childIds->isEmpty()) {
            return false;
        }

        return Question::query()
            ->whereIn('node_id', $childIds)
            ->whereHas('variants', function ($query): void {
                $query->where('audience', Audience::Rater);
            })
            ->exists();
    }
}
