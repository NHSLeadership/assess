<?php

use App\Http\Controllers\AssessmentReportPdfController;

it('returns the PDF with a Content-Length matching the byte size so truncated downloads can be detected', function () {
    $pdf = "%PDF-1.7\n\xE2\x9C\x93 binary \x00\xFF body\n%%EOF";

    $method = new ReflectionMethod(AssessmentReportPdfController::class, 'pdfResponse');
    $response = $method->invoke(new AssessmentReportPdfController(), $pdf);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->getContent())->toBe($pdf)
        ->and($response->headers->get('Content-Length'))->toBe((string) strlen($pdf))
        ->and($response->headers->get('Content-Type'))->toBe('application/pdf')
        ->and($response->headers->get('Content-Disposition'))->toBe('attachment; filename="assessment-report.pdf"')
        ->and($response->headers->get('Cache-Control'))->toContain('no-store')
        ->and($response->headers->get('Cache-Control'))->toContain('no-transform');
});
