<?php

use App\Http\Controllers\AssessmentReportPdfController;
use App\Livewire\AssessmentCompleted;
use App\Livewire\AssessmentReport;
use App\Livewire\Assessments;
use App\Livewire\FrameworkInstructions;
use App\Livewire\Frameworks;
use App\Livewire\Home;
use App\Livewire\Review;
use App\Livewire\ReviewRequest;
use App\Livewire\Summary;
use App\Livewire\Variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::any('/admin/logout', fn(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse => redirect('/logout'))->name('filament.admin.logout');
Route::any('/admin/login', fn(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse => redirect('/login'))->name('filament.admin.login');

Route::group([
    'middleware' => [
        'auth',
        // 'can:users:manage',
    ],
], function (): void {
    Route::get('/assessments/{frameworkId?}', Frameworks::class)->name('frameworks');
    Route::get('/assessment-variants/{frameworkId?}/{assessmentId?}/{back?}', Variants::class)->name('variants');
    Route::get('/framework-instructions/{frameworkId?}/{assessmentId?}', FrameworkInstructions::class)->name('instructions');

    Route::get('/summary/{frameworkId?}/{assessmentId?}', Summary::class)->name('summary');
    Route::get('/assessment/{assessmentId?}/{nodeId?}/{edit?}', Assessments::class)->name('questions');
    Route::get('/assessment-completed/{assessmentId?}', AssessmentCompleted::class)->name('assessment-completed');

    Route::get('/assessment-report/{frameworkId}/{assessmentId}', AssessmentReport::class)->name('assessment-report');
    Route::post('/assessment-report/{frameworkId}/{assessmentId}', AssessmentReportPdfController::class)
        ->name('assessment-report-pdf');

    /**
     * Request review
     */
    Route::get('/request/{assessmentId}', ReviewRequest::class)->name('review-request');
});

Route::group([
    'middleware' => 'signed',
], function (): void {
    Route::get('/review/{hashId}', Review::class)->name('review');
});

require __DIR__.'/auth.php';
