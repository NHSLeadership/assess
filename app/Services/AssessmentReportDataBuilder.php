<?php

namespace App\Services;

use Illuminate\Support\Collection;

class AssessmentReportDataBuilder
{
    /**
     * @return array{
     *     totalRaters: int,
     *     completedRaters: int,
     *     reportAvailable: bool,
     *     variantAttributeLabel: ?string,
     *     raterFeedback: Collection,
     *     radarData: array,
     *     radarOptions: array,
     *     barChartsByNode: array<int, array<string, mixed>>,
     *     barChartsList: array<int, array<string, mixed>>,
     *     signpostsByNode: array<int, array<int, mixed>>
     * }
     */
    public function build(AssessmentReportService $service, bool $respectAvailabilityGate = true): array
    {
        $ratersQuery = $service->assessment()->raters();

        $totalRaters = $ratersQuery->count();
        $completedRaters = (clone $ratersQuery)
            ->wherePivotNotNull('submitted_at')
            ->count();

        $reportAvailable = $totalRaters === 0 || $completedRaters === $totalRaters;
        $hasRaters = $totalRaters > 0;

        $radarData = [];
        $radarOptions = [];
        $raterFeedback = collect();
        $signpostsByNode = [];
        $barChartsByNode = [];
        $barChartsList = [];

        if (! $respectAvailabilityGate || $reportAvailable) {
            foreach ($service->nodes() as $node) {
                $chart = $service->barChart($node, hasRaters: $hasRaters);

                if ($chart) {
                    $barChartsByNode[$node->id] = $chart;
                    $barChartsList[] = $chart;
                }

                $signposts = $service->signpostsForNode($node);

                if ($signposts !== []) {
                    $signpostsByNode[$node->id] = $signposts;
                }
            }

            $radar = $service->radarChart(hasRaters: $hasRaters);
            $radarData = $radar['data'];
            $radarOptions = $radar['options'];
            $raterFeedback = $service->raterFeedbackByStandard();
        }

        return [
            'totalRaters' => $totalRaters,
            'completedRaters' => $completedRaters,
            'reportAvailable' => $reportAvailable,
            'variantAttributeLabel' => $service->variantAttributeLabel(),
            'raterFeedback' => $raterFeedback,
            'radarData' => $radarData,
            'radarOptions' => $radarOptions,
            'barChartsByNode' => $barChartsByNode,
            'barChartsList' => $barChartsList,
            'signpostsByNode' => $signpostsByNode,
        ];
    }
}
