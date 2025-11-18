<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::group([
    // 'middleware' => ['auth']
], function () {
    Route::get('/', \App\Livewire\Frameworks::class)->name('home');
    Route::get('/frameworks/{frameworkId?}', \App\Livewire\Frameworks::class)->name('frameworks');
    Route::get('/standards/{frameworkId?}/{stageId?}', \App\Livewire\Stages::class)->name('standards');
    Route::get('/nodes/{frameworkId?}/{assessmentId?}', \App\Livewire\Areas::class)->name('areas');
    Route::get('/assessments/{assessmentId?}', \App\Livewire\Assessments::class)->name('assessments');

    /**
     * Request review
     */
    Route::get('/request/{assessmentId}', \App\Livewire\ReviewRequest::class)->name('review-request');
});

Route::group([
    'middleware' => 'signed'
], function () {
    Route::get('/review/{hashId}', \App\Livewire\Review::class)->name('review');
});

require __DIR__.'/auth.php';
