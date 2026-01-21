<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AssessmentReportService;
use Illuminate\Support\Str;

class AssessmentReportPdfController extends Controller
{
    public function __invoke($frameworkId, $assessmentId, Request $request): \Illuminate\Http\Response
    {
        $service = new AssessmentReportService($frameworkId, $assessmentId);

        $barImagesRaw = json_decode($request->barImages, true) ?? [];

        $barImages = collect($barImagesRaw)
            ->pluck('image', 'id')
            ->toArray();

        $signposts = [];

        foreach ($service->nodes() as $node) {
            $signpostsForNode = $service->signpostsForNode($node);
            if ($signpostsForNode) {
                $signposts[$node->id] = $signpostsForNode;
            }
        }

        return Pdf::loadView('pdf.assessment-report', [
            'framework'  => $service->framework(),
            'nodes'      => $service->nodes(),
            'responses'  => $service->responses(),
            'assessment' => $service->assessment(),
            'rater'      => $service->rater(),
            'radarImage' => $request->radarImage,
            'barImages'  => $barImages,
            'barCharts'  => $service->barChartsCompetency(),
            'signposts'  => $signposts,
            'isMobile'   => false,
        ])->download('assessment-report.pdf');
    }
}
