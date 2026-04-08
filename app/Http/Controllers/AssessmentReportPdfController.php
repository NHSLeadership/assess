<?php

namespace App\Http\Controllers;

use App\Services\AssessmentReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AssessmentReportPdfController extends Controller
{
    public function __invoke($frameworkId, $assessmentId, Request $request): Response
    {
        return match (config('app.pdf_engine')) {
            'gotenberg' => $this->renderWithGotenberg($frameworkId, $assessmentId, $request),
            default     => $this->renderWithDompdf($frameworkId, $assessmentId, $request),
        };
    }


    protected function buildPdfData(
        AssessmentReportService $service,
        Request $request,
        bool $forGotenberg
    ): array {
        $barImagesRaw = json_decode($request->barImages, true) ?? [];

        $barImages = collect($barImagesRaw)
            ->pluck('image', 'id')
            ->toArray();

        $signposts = [];
        foreach ($service->nodes() as $node) {
            $sp = $service->signpostsForNode($node);
            if ($sp) {
                $signposts[$node->id] = $sp;
            }
        }

        $framework = $service->framework();

        return [
            'framework' => $framework,
            'nodes' => $service->nodes(),
            'responses' => $service->responses(),
            'assessment' => $service->assessment(),
            'rater' => $service->rater(),
            'radarImage' => $request->radarImage,
            'barImages' => $barImages,
            'barCharts' => $service->barChartsCompetency(),
            'signposts' => $signposts,
            'isMobile' => false,
            'variantAttributeLabel' => $service->variantAttributeLabel(),

            // Engine‑specific HTML handling
            'frameworkCustomHtml' => $forGotenberg
                ? $this->prepareHtmlForGotenberg(data_get($framework, 'report_html'))
                : $this->prepareHtmlForDompdf(data_get($framework, 'report_html')),
        ];
    }

    protected function renderWithDompdf(
        $frameworkId,
        $assessmentId,
        Request $request
    ): Response {
        $service = new AssessmentReportService($frameworkId, $assessmentId);

        $data = $this->buildPdfData($service, $request, false);

        logger()->info('PDF generated', [
            'engine' => config('app.pdf_engine'),
            'assessment_id' => $assessmentId,
            'user_id' => auth()?->id(),
        ]);

        return Pdf::loadView('pdf.assessment-report', $data)
            ->download('assessment-report.pdf');
    }

    protected function renderWithGotenberg(
        $frameworkId,
        $assessmentId,
        Request $request
    ): Response {
        $service = new AssessmentReportService($frameworkId, $assessmentId);

        $data = $this->buildPdfData($service, $request, true);

        $html = view('pdf.gotenberg-assessment-report', $data)->render();
        $headerHtml = view('pdf.gotenberg-header')->render();
        $footerHtml = view('pdf.gotenberg-footer')->render();

        try {
            $clientRequest = Http::timeout(120);

            if (config('app.gotenberg_basic_auth_enabled')) {
                $clientRequest = $clientRequest->withBasicAuth(
                    config('app.gotenberg_basic_auth_username'),
                    config('app.gotenberg_basic_auth_password')
                );
            }

            $response = $clientRequest
                ->attach('files', $html, 'index.html')
                ->attach('files', $headerHtml, 'header.html')
                ->attach('files', $footerHtml, 'footer.html')
                ->post(
                    rtrim(config('app.gotenberg_url'), '/') . '/forms/chromium/convert/html',
                    [
                        'marginTop' => '120px',
                        'marginBottom' => '50px',
                        'printBackground' => true,
                    ]
                );

            if ( !$response->ok()) {
                throw new \RuntimeException(
                    'Gotenberg failed with status ' . $response->status()
                );
            }

            logger()->info('PDF generated', [
                'engine' => config('app.pdf_engine'),
                'assessment_id' => $assessmentId,
                'user_id' => auth()?->id(),
            ]);

            return response($response->body(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="assessment-report.pdf"',
            ]);

        } catch (\Throwable $e) {
            logger()->warning('Using Dompdf fallback', [
                'reason' => $e->getMessage(),
            ]);
            return $this->renderWithDompdf($frameworkId, $assessmentId, $request);
        }
    }

    protected function prepareHtmlForDompdf(?string $content): string
    {
        if (! $content) {
            return '';
        }

        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
        $content = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00A0}]/u', '', $content);
        $content = preg_replace('/<\s*style\b[^>]*>[\s\S]*?<\/style>/i', '', $content);

        return preg_replace_callback(
            '/<img([^>]*)src=["\']\/media\/([^"\']+)["\']([^>]*)>/i',
            function ($m) {
                $path = public_path('media/' . $m[2]);
                return '<img' . $m[1] .
                    'src="' . $path . '" ' .
                    'style="width:350px; height:auto; display:block; margin:0 auto;"' .
                    $m[3] . '>';
            },
            $content
        );
    }

    /* -----------------------------------------------------------------
     | Gotenberg HTML rewriting (Base64)
     |-----------------------------------------------------------------*/
    protected function prepareHtmlForGotenberg(?string $content): string
    {
        if (! $content) {
            return '';
        }

        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
        $content = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00A0}]/u', '', $content);
        $content = preg_replace('/<\s*style\b[^>]*>[\s\S]*?<\/style>/i', '', $content);

        return preg_replace_callback(
            '/<img([^>]*)src=["\']\/media\/([^"\']+)["\']([^>]*)>/i',
            function ($m) {
                $path = public_path('media/' . $m[2]);

                if (! file_exists($path)) {
                    return $m[0];
                }

                $mime = mime_content_type($path);
                $base64 = base64_encode(file_get_contents($path));

                return '<img' . $m[1] .
                    'src="data:' . $mime . ';base64,' . $base64 . '" ' .
                    'class="image-adjust"' .
                    $m[3] . '>';
            },
            $content
        );
    }
}
