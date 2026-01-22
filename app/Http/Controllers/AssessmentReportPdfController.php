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

        $framework = $service->framework();
        return Pdf::loadView('pdf.assessment-report', [
            'framework'  => $framework,
            'nodes'      => $service->nodes(),
            'responses'  => $service->responses(),
            'assessment' => $service->assessment(),
            'rater'      => $service->rater(),
            'radarImage' => $request->radarImage,
            'barImages'  => $barImages,
            'barCharts'  => $service->barChartsCompetency(),
            'signposts'  => $signposts,
            'isMobile'   => false,
            'frameworkCustomHtml'   => $this->getSanitisedHtml(data_get($framework, 'report_html')),
        ])->download('assessment-report.pdf');
    }

    public function getSanitisedHtml($content)
    {
        // Get the raw HTML
        if (empty($content)) {
            return '';
        }
        // Decode HTML entities first (important)
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
        // Remove invisible Unicode characters (common from RTE)
        $content = preg_replace('/[\x{200B}\x{200C}\x{200D}\x{FEFF}\x{00A0}]/u', '', $content);
        // Remove ANY <style>...</style> block (multi-line, nested, malformed)
        $content = preg_replace('/<\s*style\b[^>]*>[\s\S]*?<\s*\/\s*style\s*>/i', '', $content);

        return trim($content);
    }

}
