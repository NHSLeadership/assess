<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AssessmentReportService;

class AssessmentReportPdfController extends Controller
{
    public function __invoke($frameworkId, $assessmentId, Request $request): \Illuminate\Http\Response
    {
        $service = new AssessmentReportService($frameworkId, $assessmentId);

        $barImagesRaw = json_decode($request->barImages, true) ?? [];

        $barImages = collect($barImagesRaw)
            ->pluck('image', 'id')
            ->toArray();

        return Pdf::loadView('pdf.assessment-report', [
            'framework'  => $service->framework(),
            'nodes'      => $service->nodes(),
            'responses'  => $service->responses(),
            'assessment' => $service->assessment(),
            'rater'      => $service->rater(),
            'radarImage' => $request->radarImage,
            'barImages'  => $barImages,
            'barCharts'  => $service->barCharts(),
        ])->download('assessment-report.pdf');
    }
}
