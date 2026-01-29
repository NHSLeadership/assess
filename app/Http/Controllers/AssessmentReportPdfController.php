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
            'frameworkCustomHtml'   => $this->prepareHtmlForPdf(data_get($framework, 'report_html')),
            'variantAttributeLabel' => $service->variantAttributeLabel(),
        ])->download('assessment-report.pdf');
    }

    public function prepareHtmlForPdf(?string $content = null): string
    {
        if (empty($content)) {
            return '';
        }

        // Decode HTML entities
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');

        // Remove invisible Unicode characters
        $content = preg_replace('/[\x{200B}\x{200C}\x{200D}\x{FEFF}\x{00A0}]/u', '', $content);

        // Remove <style>...</style> blocks
        $content = preg_replace('/<\s*style\b[^>]*>[\s\S]*?<\s*\/\s*style\s*>/i', '', $content);

        // Rewrite /media/... → absolute filesystem path
        $content = preg_replace_callback(
            '/<img([^>]*)src=[\'"]\/media\/([^\'"]+)[\'"]([^>]*)>/i',
            function ($m) {
                $before = $m[1];   // attributes before src
                $file   = $m[2];   // wheel.png
                $after  = $m[3];   // attributes after src

                $path = public_path('media/' . $file);

                return '<img' . $before . 'src="' . $path . '" style="width:350px; height:auto; display:block; margin-left:auto; margin-right:auto;"' . $after . '>';
            },
            $content
        );
        return trim($content);
    }


}
